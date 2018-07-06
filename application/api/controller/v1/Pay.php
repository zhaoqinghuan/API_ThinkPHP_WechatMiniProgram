<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/5 11:25
 * 描  述:
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
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
     *  自定义请求预订单信息方法
     *  预订单需要去微信服务器去发起一个预订单请求
     *  @id 当前支付对应的订单号
     */
    public function getPreOrder($id='')
    {
        //  参数校验
        (new IDMustBePostiveInt())->goCheck();

    }
}