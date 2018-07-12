<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/5 11:25
 * 描  述:
 */

namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\service\PayService;
use app\api\service\WxNotify;
use app\api\validate\IDMustBePostiveInt;

class Pay extends BaseController
{
    /**
     *  为当前接口添加权限管理，只有用户能访问
     */
    protected $beforeActionList = [
        'checkExclusiveScope' => [
            //  在执行getPreOrder方法前先执行checkExclusiveScope方法进行权限管理
            'only' => 'getPreOrder'
        ]
    ];

    /**
     *  对微信支付回调结果进行调试的方法
     */
    public function re_notify()
    {
        // TODO 调试微信支付回调结果
    }

    /**
     *  接收微信服务器返回的支付结果方法
     */
    public function receiveNotify()
    {
//        $xmlData = file_get_contents('php://input');//  收取微信支付回调的参数
//        $result = curl_post_raw('http://api.huiqinsoft.com/api/v1/pay/re_notify?XDEBUG_SESSION_START=13314',
//            $xmlData);  //  调用自定义的curl_形式的post请求并携带参数，在这里的URL中携带XDEBUG_SESSION_START参数
//        die;
        //  微信支付结果对于当前接口的调用不是只调用一次，而是每隔一定的时间就进行一次调用
        //  其调用频率为每隔15/15/30/180/1800/1800/1800/1800/3600   (单位：秒)
        //  如果你正确处理了微信支付回调结果并按照文档返回了信息就不会重复调用！
        //  当前接口的特点:必须是POST形式接口;微信返回的结果是XML格式;路由中不允许携带“？及 参数”
        $notify = new WxNotify();// 实例化自定义微信支付回调Service
        //  这里不能直接调用重写的微信支付SDK回调方法，需要调用微信支付SDK回调方法入口方法
        return $notify->Handle();
    }

    /**
     *  自定义请求预订单信息方法
     *  预订单需要去微信服务器去发起一个预订单请求
     *  @id 当前支付对应的订单号
     */
    public function getPreOrder($id='')
    {
        //  参数校验
        (new IDMustBePostiveInt())->goCheck();
        //  在控制器中实例化PayService层
        $pay = new PayService($id);
        //  调用PayService层下的Pay方法
        return $pay->pay();
    }


}