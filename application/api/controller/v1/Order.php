<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/27 15:09
 * 描  述:
 */

namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\service\OrderService;
use app\api\service\UserToken as TokenService;
use app\api\validate\OrderPlace;

class Order extends BaseController
{
    /**
     *  自定义前置操作方法
     */
    protected $beforeActionList = [
        //  前置方法名
        'checkExclusiveScope' => [
            //  需要使用前置方法的方法名
            'only' => 'placeOrder'
        ]
    ];

    //  创建下单接口
    public function placeOrder()
    {
        //  调用自定义参数验证类
        (new OrderPlace())->goCheck();
        //  获取客户端传递过来的参数
        //  因为传递的参数是数组类型所以需要加/a
        $products = input('post.products/a');
        $uid = TokenService::getCurrentUid();
        //  实例化Service模型
        $order = new OrderService();
        //  调用订单Service层中创建的下单方法
        $status = $order->place($uid,$products);
        //  返回信息
        return $status;
    }
}