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
use app\api\validate\IDMustBePostiveInt;

class Product
{
    /**
     *  分类关联的商品信息
     *  @url    /product/by_category
     *  @http   GET
     *  @id     对应的当前商品分类主键
     */
    public function getAllInCategory($id)
    {
        //  参数校验是否为int类型正整数
        (new IDMustBePostiveInt())->goCheck();
        //  调用模型下的静态方法获取查询结果
        $products = ProductModel::getProductsByCategoryID($id);
        //  对结果进行判空验证
        if($products->isEmpty()){
            //  如果结果是空抛出异常
            throw new ProductException();
        }
        //  对结果中的summer字段进行隐藏
        $Products = $products->hidden(['summary']);
        //  向客户端返回查询结果
        return $Products;
    }

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