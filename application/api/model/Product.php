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
     *  创建根据ID查询商品表某一商品详细信息的方法
     *  @param  $id
     *  @return $product
     */
    public static function getProductDetail($id)
    {
        //  调用关联模型执行查询操作
        //  这里使用的Query查询构造器，每一个with关联模型都会返回一个Query对象
        //  1.将第一个with关联模型中的普通数组改变为一个关联数组的形式 键对应当前的关联关系 值是一个闭包函数
        //  2.给闭包函数传递一个Query对象（查询构造器）做为值
        //  3.因为这里的with是Query查询构造器下的一个方法因此我们可以在闭包函数传入query参数后使用这个with方法
        //  4.给with方法传入关联模型文件下的关联关系方法让当前的关联模型再关联一组关联关系
        //  5.对当前模型使用链式方法下的排序操作对结果进行排序操作
        //  这里的方法等于是将多层关联模型首先进行分层，到达关联imgUrl这一层的时候直接在这一层执行查询操作时进行排序查询，
        $product = self::with([
            'imgs'=>function($query){
                $query->with(['imgUrl'])
                ->order('order','asc');
            }
        ])
            ->with(['properties'])
            ->find($id);
        return $product;
    }

    /**
     *  创建商品信息表与商品详细信息图片信息表的关联关系
     *  多对一关联关系使用hasMany
     *  参数一 关联模型文件名 参数二 关联表外键 参数三 关联到当前表的主键
     */
    public function imgs()
    {
        return $this->hasMany('ProductImage','product_id','id');
    }

    /**
     *  创建商品信息表与产品参数表的关联关系
     *  多对一关联关系使用hasMany
     *  参数一 关联模型文件名 参数二 关联表外键 参数三 关联到当前表的主键
     */
    public function properties()
    {
        return $this->hasMany('ProductProperty','product_id','id');
    }

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