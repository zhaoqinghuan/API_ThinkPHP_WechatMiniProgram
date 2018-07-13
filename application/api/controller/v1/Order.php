<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/27 15:09
 * 描  述:
 */

namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\lib\exception\OrderException;
use app\api\service\OrderService;
use app\api\service\UserToken as TokenService;
use app\api\validate\IDMustBePostiveInt;
use app\api\validate\OrderPlace;
use app\api\validate\PagingParameter;
use app\api\model\Order as OrderModel;

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
        ],
        //  定义前置方法名为允许当前用户和管理员访问
        'checkPrimaryScope' => [
            'only' => 'getDetail,getSummaryByUser'
        ]
    ];

    /**
     *  获取当前用户的订单列表简要信息接口
     * @param $page  integer 当前页数
     * @param $size  integer 每页显示记录数
     * @return $data array   订单列表及分页数据
     */
    public function getSummaryByUser($page=1 , $size=15)
    {
        //  调用验证器进行参数校验
        (new PagingParameter())->goCheck();
        //  获取当前请求对应的用户的UID
        $uid = TokenService::getCurrentUid();
        //  调用模型下的方法获取订单数据
        $pagingOrders = OrderModel::getSummaryByUser($uid,$page,$size);
        //  对订单数据进行判空处理 因为获取到的结果是一个对象因此只能用对象下的方法进行判断
        if($pagingOrders->isEmpty()){
            //  未取到结果返回NULL以及当前页码 这里不抛出异常主要是为了客户端已于维护
            return [
                'data' => [],
                'current_page' => $pagingOrders->getCurrentPage()
            ];
        }
        //  将获取到的结果生成数组形式
        $data = $pagingOrders
            ->hidden(['prepay_id','snap_items','snap_address'])//  在生成数组的同时将客户端当前用不到的参数进行隐藏
            ->toArray();
        //  不为空将数据进行结构化处理并返回给客户端，同样需要返回当前页码
        return [
            'data' => $data,
            'current_page' => $pagingOrders->getCurrentPage()
        ];
    }

    /**
     *  获取某一条订单的详细信息
     *  @param $id   integer 订单ID
     *  @return  $orderDetail array 订单详细数据
     */
    public function getDetail($id)
    {
        //  参数校验
        (new IDMustBePostiveInt())->goCheck();
        //  调用模型下的方法进行查询操作
        $orderDetail = OrderModel::get($id);
        if(!$orderDetail){
            throw new OrderException();
        }
        return $orderDetail->hidden(['prepay_id']);
    }


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