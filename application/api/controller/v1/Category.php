<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/13 17:12
 * 描  述:
 */

namespace app\api\controller\v1;

use app\api\lib\exception\CategoryException;
use app\api\model\Category as CategoryModel;

class Category
{
    /**
     *  获取所有分类列表信息
     *  @url    category/all
     *  @Return Array当前数据库中的所有分类信息
     */
    public function getAllCategories()
    {
        //  直接调用模型下自定义的静态方法实现查询所有分类信息
        $categories = CategoryModel::getAllCategoriesInfo();
        //  对结果进行判空处理
        if($categories->isEmpty()){
            throw new CategoryException();
        }
        return $categories;
    }
}