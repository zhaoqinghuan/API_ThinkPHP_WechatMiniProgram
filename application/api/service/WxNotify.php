<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/10 15:51
 * 描  述:
 */

namespace app\api\service;

use app\api\lib\enum\OrderStatusEnum;
use app\api\model\Order;
use app\api\model\Product;
use think\Db;
use think\Exception;
use think\Loader;
use think\Log;

//  引入微信支付SDK的入口文件
Loader::import('WxPay.WxPay',EXTEND_PATH,'.Api.php');
//  直接继承微信支付的回调结果方法
class WxNotify extends \WxPayNotify
{
    /**
     *    微信支付回调结果返回的结果是XML格式数据，对其处理比较麻烦，直接使用微信支付SDK中的方法
     *    微信支付中的回调结果处理方法是一个空方法，只能讲结果转换成数组格式，实际上对支付结果的处理
     *    还需要我们手动定义，微信提供了一个结果处理方法，我们需要继承并重写该方法
     */
    //  将处理回调结果的方法进行重写
    public function NotifyProcess($data, &$msg)
    {
        //  先对支付结果进行判断，是否支付成功（结果不同处理方法不同）
        //  参考支付回调结果文档 支付是否成功就是看result_code参数的值
        if($data['result_code'] == 'SUCCESS'){
            //  支付成功的处理方法
            //  取当前订单的订单编号
            $orderNo = $data['out_trade_no'];
            //  开启事务
            Db::startTrans();
            try{
                //  利用回调结果返回的支付订单号查询订单商品信息
                $order = Order::where('order_no','=',$orderNo)
                    ->lock(true)//  为SQL语句添加锁，锁并不能替代事务处理这种应对高并发问题
                    ->find();
                //  对订单信息进行判断，如果订单已被支付，无需再进行后续处理
                if($order->status == 1){
                    //  调用OrderService方法中的库存量检测方法(checkOrderStock)
                    $service = new OrderService();
                    $stockStatus = $service->checkOrderStock($order->id);
                    //  对订单库存检测结果进行判断
                    if($stockStatus['pass']){
                        $this->updateOrderStatus($order->id, true);//  调用订单状态修改方法
                        $this->reduceStock($stockStatus);//  调用库存量修改方法
                    }else{//    如果库存不足只需要修改订单状态，不进行库存修改
                        $this->updateOrderStatus($order->id, false);//  调用订单状态修改方法
                    }
                }
                Db::commit();//  提交事务
                return true;    //  处理成功，向微信服务器返回True
            }catch (Exception $exception){
                //  事务回滚
                Db::rollback();
                //  处理失败，信息记录日志，返回false
                Log::error($exception);
                return false;
            }
        }else{
            //  处理支付失败，直接返回true,
            //  订单支付失败本身服务器也未对订单状态进行处理，且不再需要服务器重复发送订单支付结果
            return true;
        }
    }

    //  订单状态修改方法
    private function updateOrderStatus($orderID,$success)
    {
        //  success参数代表订单库存足够还是不够
        //  三目运算符如果订单足够将订单状态修改为已支付，否则状态修改为已支付但库存不足
        $status = $success?
            OrderStatusEnum::PAID :
            OrderStatusEnum::PAID_BUT_OUT_OF;
        //  根据订单编号修改订单状态
        Order::where('id','=',$orderID)->update([
            'status' => $status
        ]);
    }

    //  库存量修改方法
    private function reduceStock($stockStatus)
    {
        //  根据订单商品信息，对商品库存进行修改
        //  循环获取当前订单下的商品信息
        foreach ($stockStatus['pStatusArray'] as $signlePStatus){
            //  直接对商品库存递减一个订单商品数量
            Product::where('id','=',$signlePStatus['id'])
                ->setDec('stock',$signlePStatus['count']);
            //  直接调用框架提供的字段数值递减方法，参数一需要递减的字段，参数二需要递减的数量
        }
    }

}