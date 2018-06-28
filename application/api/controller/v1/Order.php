<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/27 15:09
 * 描  述:
 */

namespace app\api\controller\v1;
use app\api\controller\BaseController;
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

    }
}