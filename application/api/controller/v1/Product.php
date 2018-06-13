<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/13 11:39
 * 描  述:
 */

namespace app\api\controller\v1;


use app\api\validate\CountValidate;
use app\api\model\Product as ProductModel;
use app\api\lib\exception\ProductException;

class Product
{
    /**
     *  自定义商品信息接口
     *  @url    /product/recent
     *  @http   GET
     */
    public function getRecent($count=15)
    {
        //  调用自定义验证器进行参数校验
        (new CountValidate())->goCheck();
        //  调用自定义模型下的链式查询语句
        $Products = ProductModel::getMostRecent($count);
        //  对查询结果进行判空
        if($Products->isEmpty()){
            //  调用自定义异常处理方法
            throw new ProductException();
        }
//        //  将查询结果转化为数据集对象
//        $collection = collection($Products);
//        //  调用数据集对象下的hidden方法隐藏字段
//        $Products = $collection->hidden(['summary']);
        $Products = $Products->hidden(['summary']);

        return $Products;
    }
}