<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/28 17:15
 * 描  述:
 */
namespace app\api\service;
use app\api\lib\exception\OrderException;
use app\api\lib\exception\UserException;
use app\api\model\Product;
use app\api\model\UserAddress;
use think\Exception;

class OrderService extends BaseService
{
    //  订单的商品列表，客户端传递过来的订单信息(oProducts)
    protected $oProducts;
    //  数据库中实时的商品数据信息(products)
    protected $products;
    //  数据提交用户的UID
    protected $uid;
    //  自定义下单方法
    public function place($uid, $oProducts)
    {
        //  对成员属性进行赋值
        $this->uid = $uid;
        $this->oProducts = $oProducts;
        $this->products = $this->getProductsByOrder($oProducts);
        //  调用获取订单详细信息的方法
        $status = $this->getOrderStatus();
        //  商品库存不足订单返回值定义
        if(!$status['pass']){
            //  如果订单通过库存量检测，会进入到订单写入状态，成功后会返回订单信息主键，
            //  为保持接口返回信息的一致性，这里返回-1
            $status['order_id'] = -1;
            return $status;
        }
        //  库存充足开始订单创建
        $orderSnap = $this->snapOrder($status);//生成订单快照
        //  调用订单创建的方法
        $order = $this->createOrder($orderSnap);
        //  重构订单创建方法成功后返回的数据信息
        $order['pass'] = true;  //  新增一条订单整体城建成功的信息
        return $order;          //  信息返回
    }

    /**
     *  创建订单方法
     *  $snap 订单快照数据
     */
    private function createOrder($snap)
    {
        //  异常处理
        try{
            //  获取订单编号
            $orderNo = $this->makeOrderNo();
            //  实例化模型文件 如果直接实例化不引入的话这里需要些完整路径
            $order = new \app\api\model\Order();
            //  为模型对应数据表中的数据一一赋值
            $order->user_id = $this->uid;                       //  订单对应用户UID
            $order->order_no = $orderNo;                        //  订单编号
            $order->total_price = $snap['orderPrice'];          //  订单总价
            $order->total_count = $snap['totalCount'];          //  订单商品总数
            $order->snap_img = $snap['snapImg'];                //  订单头像
            $order->snap_name = $snap['snapName'];              //  订单名称
            $order->snap_address = $snap['snapAddress'];        //  订单收货地址
            $order->snap_items = json_encode($snap['pStatus']); //  订单商品详细信息进行json序列化
            //  调用订单的存储方法将订单数据存储
            $order->save();
            //  获取订单存储成功后的订单表主键ID和创建时间信息
            $orderID = $order->id;  //  直接获取订单表中的新增的id信息
            $create_time = $order->create_time;  //  直接获取订单表中的新增数据的创建时间
            //  循环获取客户端传递过来的订单信息 注意使用&引用符
            foreach ($this->oProducts as &$p){
                //  循环向订单与商品信息关联表中的每一项商品中添加订单主键ID信息
                $p['order_id'] = $orderID;
            }
            //  实例化模型文件
            $orderProduct = new \app\api\model\OrderProduct();
            //  调用批量存储方法进行订单数据存储
            $orderProduct->saveAll($this->oProducts);
            //  返回数据
            return[
                'order_no' => $orderNo,
                'order_id' => $orderID,
                'create_time' => $create_time
            ];
        }catch (Exception $ex){
            //  如果抓取到异常直接将异常抛出
            throw $ex;
        }
    }
    /**
     *  生成订单编号的方法
     */
    public static function makeOrderNo()
    {
        //  订单编号首位的年份信息字母组合
        $yCode = array('A','B','C','D','E','F','G','H','I','J');
        //  第一位用当前时间的年份减去2018的结果如果是0取$yCode数组中的第一个元素，并将其转换为整型（只有个位）
        //  第二位将当前时间的月份转换为16进制的数值，
        //  第三位将当前时间的日获取其时间戳
        //  第四位获取当前时间戳的微秒数
        //  最后再取0,99之间的随机数一个
        $orderSn = $yCode[intval(date('Y')) - 2018] . strtoupper(dechex(date('m')))
            . date('d') . substr(time(),-5) . substr(microtime(),2,5)
            . sprintf('%02d',rand(0,99));
        return $orderSn;
    }

    /**
     *  生成订单快照的方法
     *
     */
    private function snapOrder($status)
    {
        //  快照内容框架
        $snap = [
            'orderPrice' => 0,  //  订单总价
            'totalCount' => 0,  //  订单商品总数
            'pStatus' => [],    //  订单商品状态
            'snapAddress'=> '', //  订单用户收货地址信息
            'snapName' => '',   //  订单名称信息
            'snapImg'  => ''    //  订单商品头像信息
         ];
        //  快照内容填充
        //  订单商品总价等于获取订单时计算的信息
        $snap['orderPrice'] = $status['orderPrice'];
        //  订单商品总数等于获取订单时计算的信息
        $snap['totalCount'] = $status['totalCount'];
        //  订单商品信息等于获取订单时计算的信息
        $snap['pStatus'] = $status['pStatusArray'];
        //  订单用户收货地址信息实时去地址信息表里获取
        $snap['snapAddress'] = json_encode($this->getUserAddress());
        //  订单名称用当前订单中的第一个商品的名称
        $snap['snapName'] = $this->products[0]['name'];
        //  订单头像用当前订单中的第一个商品的头像
        $snap['snapImg'] = $this->products[0]['main_img_url'];

        //  判断当前订单下的商品数，为订单名称重新赋值
        if(count($this->products) > 1){
            $snap['snapName'] .= '等';
        }
        return $snap;
    }

    /**
     *  获取当前用户地址信息的方法
     */
    private function getUserAddress()
    {
        //  直接调用UserAddress模型进行where查询
        $userAddress = UserAddress::where('user_id','=',$this->uid)
            ->find();
        //  判断当前用户的地址信息是否存在
        if(!$userAddress){
            throw new UserException([
               'msg' => '用户收货地址不存在，下单失败',
               'errorCode' => 60001,
            ]);
        }
        //  用模型查询出来的结果是对象将其转换为数组，以数组形式返回地址信息
        return $userAddress->toArray();
    }

    /**
     *  获取当前订单的详细信息
     */
    protected function getOrderStatus()
    {
        //  定义返回值的数据结构
        $status = [
            //  商品库存能否满足订单需要
            'pass' => true,
            //  当前订单的总价
            'orderPrice' => 0,
            //  当前订单商品总数
            'totalCount' => 0,
            //  存储订单商品的详细信息
            'pStatusArray' => []
        ];
        //  进行库存量对比
        foreach ($this->oProducts as $oProduct){
            //  依次调用库存检测方法体进行库存检测
            $pStatus = $this->getProductStatus(
              $oProduct['product_id'],$oProduct['count'],$this->products
            );
            //  根据结果重构数据结果赋值给订单状态
            if(!$pStatus['haveStock']){
                $status['pass'] = false;
            }
            //  订单总价计算并赋值
            $status['orderPrice'] += $pStatus['totalPrice'];
            //  订单商品总数计算并赋值
            $status['totalCount'] += $pStatus['count'];
            //  将订单详细信息存储
            array_push($status['pStatusArray'],$pStatus);
        }
        return $status;
    }

    /**
     *  自定义进行库存量对比的方法，
     *  需要三个参数 订单商品主键ID($oPID) 订单商品的数量($oCount) 库存商品信息($products)
     */
    private function getProductStatus($oPID,$oCount,$products)
    {
        //  定义一个变量用来保存商品数据库主键ID
        $pIndex = -1;
        //  保存当前订单商品的详细信息
        $pStatus = [
            'id' => null,// 商品ID
            'haveStock' => false,// 库存是否充足
            'count' => 0,   //  当前订单下商品的数量
            'name' => '',   //  商品名称
            'totalPrice' => 0.00,
        ];
        //  循环到库存信息中查找商品ID和库存商品ID信息相同的数据
        for ($i=0;$i<count($products);$i++){
            //  查找订单商品主键ID中等于数据库查询商品信息的商品ID
            if($oPID == $products[$i]['id']){
                //  将商品主键ID赋值给变量
                $pIndex = $i;
            }
        }
        //  判断商品主键ID保存是否正常，防止客户端传递错误信息导致服务器错误
        if($pIndex == -1){
            //  进行异常处理
            throw new OrderException([
                'msg' => '商品ID为'.$oPID.'的商品不存在，订单创建失败'
            ]);
        }else{
            //  进行库存量检测
            $products = $products[$pIndex];
            $pStatus['id'] = $products['id'];
            $pStatus['count'] = $oCount;
            $pStatus['name'] = $products['name'];
            $pStatus['totalPrice'] = $products['price'] * $oCount;
            if($products['stock'] - $oCount >= 0){
                //  判断如果商品库存值减去当前订单下商品数量大于/等于0说明商品库存充足
                //  修改订单商品库存为充足，
                $pStatus['haveStock'] = true;
            }
        }
        return $pStatus;
    }

    //  自定义根据订单信息查找对应的商品库存信息
    private function getProductsByOrder($oProducts)
    {
        $oPIDs = [];
        //  将oProducts中的商品ID提取成数组
        foreach ($oProducts as $item){
            array_push($oPIDs,$item['product_id']);
        }
        //  将数组作为参数进行查询，并且对结果字段进行控制
        //  visible参数只显示定义的参数
        //  toArray将结果展示为数组形式(当前默认为数据集格式)
        $products = Product::all($oPIDs)
            ->visible(['id','price','stock','name','main_img_url'])
            ->toArray();
        return $products;
    }
}