<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/11 16:07
 * 描  述:
 */

namespace app\api\model;


class Product extends BaseModel
{
    //  建立商品信息模型的字段隐藏信息
    //  theme_product表中的关联关系信息
    protected $hidden = [
        'delete_time','create_time','update_time','pivot',
        'from','main_img_id','category_id'
    ];
    /**
     *  自定义静态方法根据商品分类ID查询对应的商品信息
     *  @parame $id
     */
    public static function getProductsByCategoryID($categoryID)
    {
        //  直接调用框架提供的查询方法进行条件查询
        //  查询商品表中category_id等于传递进来的分类ID的所有商品
        $products = self::where('category_id','=',$categoryID)
            ->select();
        return $products;
    }

    /**
     *  自定义静态方法查询当前模型下指定条数的商品信息
     *  @parame $count
     */
    public static function getMostRecent($count)
    {
        //  直接调用模型进行查询链式操作limit(查询条数)order(查询条件)desc(倒叙排列)
        $products = self::limit($count)
            ->order('create_time desc')
            ->select();
        return $products;
    }

    /**
     *  自定义读取器来实现自动补全Url
     *  注意读取器的定义逻辑 Get（Http动词）MainImgUrl（数据库字段名）Attr（固定参数）
     *  @value  当前需要修改的字段
     *  @data   完整的数据表相关字段信息
     */
    public function getMainImgUrlAttr($value,$data)
    {
        //  直接把参数传递给自定义基类模型下的读取器方法
        return $this->prefixImgUrl($value,$data);
    }
}