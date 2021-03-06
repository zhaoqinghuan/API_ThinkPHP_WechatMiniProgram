<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/5 11:48
 * 描  述:
 */

namespace app\api\service;


use app\api\lib\enum\OrderStatusEnum;
use app\api\lib\exception\OrderException;
use app\api\lib\exception\TokenException;
use app\api\model\Order as OrderModel;
use think\Exception;
use think\Loader;
use think\Log;

//  使用框架提供的第三方类库加载方法引入微信支付SDK的入口文件
//  参数一：目录名.文件名前缀  参数二：所属目录（extend目录属于框架提供的第三方SDK放置目录）
//  参数三：文件的后缀名
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');

class PayService extends BaseService
{

    private $orderID;//  自定义私有订单ID属性
    private $orderNO;//  自定义私有订单编号属性

    //  构造方法
    function __construct($orderID)
    {
        //  订单编号不能为空
        if(!$orderID){
            throw new Exception('订单编号不允许为NULL');
        }
        //  订单编号赋值给成员变量
        $this->orderID = $orderID;
    }

    //  发起支付的主体方法
    public function pay()
    {
        //  先进行订单信息检测
        $this->checkOrderValid();
        //  实例化OrderService
        $orderService = new OrderService();
        //  调用OrderService下的根据订单编号检测商品库存方法
        $status = $orderService->checkOrderStock($this->orderID);
        //  如果订单商品库存未通过检测进行异常返回
        if(!$status['pass']){
            return $status;
        }
        //  调用生成微信支付预订单信息方法
        return $this->makeWxPreOrder($status['orderPrice']);
    }

    //  生成微信支付的预订单信息
    private function makeWxPreOrder($totalPrice)
    {
        //  获取用户的openID
        $openid = BaseService::getCurrentTokenVar('openid');
        if(!$openid){
            //  openid无法正常获取抛出Token获取异常错误
            throw new TokenException();
        }
        //  实例化微信支付接口中的统一下单输入对象方法
        $wxOrderDate = new \WxPayUnifiedOrder();
        //  为实例化后的统一下单输入对象赋值
        //  订单号赋值
        $wxOrderDate->SetOut_trade_no($this->orderNO);
        //  交易类型，文档要求填写JSAPI
        $wxOrderDate->SetTrade_type('JSAPI');
        //  此次交易的订单总价格
        $wxOrderDate->SetTotal_fee($totalPrice);
        //  此次交易的订单总价格 微信默认价格以分为单位因此给总价乘以100
        $wxOrderDate->SetTotal_fee($totalPrice*100);
        //  对当前订单的简要描述
        $wxOrderDate->SetBody('订单简要描述');
        //  当前支付发起用户的OpenID
        $wxOrderDate->SetOpenid($openid);
        //  设置微信支付的回调地址
        $wxOrderDate->SetNotify_url(config('wxSetting.pay_back_url'));
        //  调用当前类下的getPaySignature方法
        return $this->getPaySignature($wxOrderDate);
    }

    //  用生成好的预订单信息调用微信支付sdk的预订单接口
    private function getPaySignature($wxOrderDate)
    {
        //  调用WxPay.api.php下的unifiedOrder方法实现调用微信支付预订单接口
        $wxOrder = \WxPayApi::unifiedOrder($wxOrderDate);
        //  判断接口调用是否成功
        //  根据经验这样判断接口调用是否成功
        if($wxOrder['return_code']!= 'SUCCESS' ||
            $wxOrder['result_code']!= 'SUCCESS'){
            //  将错误结果记录到日志中
            Log::record($wxOrder,'error');
            Log::record('获取预订单支付订单失败','eror');
        }
        //  调用处理$wxOrder下的prepay_id字段信息的方法
        $this->recordPreOrder($wxOrder);
        //  调用生成小程序支付参数的方法将参数返回给小程序
        $signature = $this->sign($wxOrder);

        //return $wxOrder;    //  将微信的返回结果返回到客户端。
        return $signature;    //  将微信的返回结果返回到客户端。
    }
    //  生成签名的私有方法
    private function sign($wxOrder)
    {
        //  这里可以自己通过官方文档自己手写生成签名算法
        //  微信支付SDK也提供一个直接生成签名的算法
        //  调用微信支付SDK中的生成签名的算法
        $jsApiPayData = new \WxPayJsApiPay();
        //  配置当前小程序APPID参数
        $jsApiPayData->SetAppid(config('wxSetting.app_id'));
        //  配置当前时间戳 time()函数生成时间戳(string)将时间戳强转为字符串
        $jsApiPayData->SetTimeStamp((string)time());
        //  定义生成随机字符串的方法
        $rand = md5(time().mt_rand(0,1000));
        //  配置随机字符串
        $jsApiPayData->SetNonceStr($rand);
        //  配置统一下单接口返回的prepay_id参数 注意格式！！！！！
        $jsApiPayData->SetPackage('prepay_id='.$wxOrder['prepay_id']);
        //  配置签名的算法
        $jsApiPayData->SetSignType('md5');
        //  先将上述参数调用SDK提供的生成结果方法生成客户端能用的结果
        $rawValues = $jsApiPayData->GetValues();
        //  生成签名 直接调用SDK中的生成签名方法
        $sign = $jsApiPayData->MakeSign();
        //  将签名也写入$rawValues结果中
        $rawValues['paySign'] = $sign;
        //  对SDK生成的rawValues参数进行过滤 现在返回的结果中appId参数客户端不需要使用
        unset($rawValues['appId']);
        //  结果返回给客户端
        return $rawValues;
    }

    //  处理wxOrder中的相关数据
    private function recordPreOrder($wxOrder)
    {
        //  先把$wxOrder下的prepay_id信息更新给订单表中当前订单的prepay_id字段
        OrderModel::where('id','=',$this->orderID)->update([
            'prepay_id' => $wxOrder['prepay_id']
        ]);

    }

    //  对当前的订单进行其他检测
    private function checkOrderValid()
    {
        //  01.调用Order模型的查询方法检测订单是否存在
        $order = OrderModel::where('id','=',$this->orderID)
            ->find();
        if(!$order){
            //  无需对结果进行重写，当前的异常返回结果符合要求
            throw new OrderException();
        }
        //  02.检测当前订单的创建用户和当前发起请求的操作用户是否为同一个人
        //  这个方法考虑到通用性将其封装到BaseService中
        if(!BaseService::isValidOperate($order->user_id)){
            //  抛出令牌异常
            throw new TokenException([
                 'msg' => '订单与用户不匹配',
                 'errorCode' => 10003
            ]);
        }
        //  03.检测订单是否已经被支付
        //  订单表的Status字段代表当前订单的状态
        if($order->status != OrderStatusEnum::UNPAID){
            throw new OrderException([
                'msg' => '当前订单已被支付',
                'errorCode' => 80003,
                'code' => 400
            ]);
        }
        //  将订单编号赋值给成员变量$orderNO，这里能够直接赋值就没有必要再在外边重新进行查询了
        $this->orderNO = $order->order_no;
        //  如果没有错返回订单检测成功
        return true;
    }
}