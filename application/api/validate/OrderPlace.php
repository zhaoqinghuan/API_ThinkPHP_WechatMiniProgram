<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/28 15:38
 * 描  述:
 */
namespace app\api\validate;
use app\api\lib\exception\ParameterException;
class OrderPlace extends BaseValidate
{
    //  需要验证得参数以及验证规则
    protected $rule = [
        'products' => 'checkProducts'
    ];

    //  自定义对于订单商品信息中二维数组第二纬的验证规则
    protected $singleRule = [
        'product_id' => 'require|isPositiveInteger',
        'count' => 'require|isPositiveInteger'
    ];

    /**
     *  自定义验证规则
     *  因为这里的验证规则比较特殊基本只会在当前方法中使用，所以将其定义在当前文件中
     */
    protected function checkProducts($values)
    {
        //  1.判断传入的数据类型本身是一个数组
        if (!is_array($values)){
            throw new ParameterException([
                    'msg' => '参数类型异常'
                ]);
        }
        //  2.先判断传入的数据不为空
        if (empty($values))
        {
            throw new ParameterException([
                    'msg' => '商品信息不能为空'
                ]);
        }
        //  3.将数据循环并且对主键和外键进行验证是否符合要求
        foreach ($values as $value){
            $this->checkProduct($value);
        }
        return true;
    }

    /**
     *  自定义二纬数组第二纬数据的验证
     * */
    private function checkProduct($value)
    {
        //  这里的实例化和上边的继承实际并不冲突，
        //  因为BaseValidate只是我们对框架提供的验证器类的一个封装，
        //  源代码中是要调用Check方法进行验证，经过封装我们将其重写了goCheck方法
        //  这两个方法本身并不冲突，因此我们在这里调用的直接是实例化自定义类的方法
        //  就相当于调用框架为我们定义的验证类。所以在下面直接使用check方法即可！
        //  这里是面向对象封装性的一个很使用的例子，在避免逻辑代码重构的条件下
        //  对其进行进一步封装使得能够更好的配合我们的使用
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '商品列表参数错误'
            ]);
        }
    }
}