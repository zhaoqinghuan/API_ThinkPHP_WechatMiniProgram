<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/11 16:07
 * 描  述:
 */

namespace app\api\model;
class Theme extends BaseModel
{
    //  创建需要隐藏的字段信息
    protected $hidden = [
      'delete_time',
      'topic_img_id',
      'head_img_id',
      'update_time'
    ];
    /**
     *  创建查询单个主题详细信息的方法（查询主题及相关商品信息）
     *  调用当前模型下定义的关联模型的关系执行查询操作
     *
     * */
    public static function getThemeWithProducts($id)
    {
        //  这里在进行模型关联查询的时候同样获取了当前用不到的topicImg，这是因为RestAPI
        //  是基于资源类型的Api，我们提供给客户端一个通用的接口客户端再根据自己的需要对数据进行过滤
        $theme = self::with('products,topicImg,headImg')
            ->find($id);
        return $theme;
    }
    /**
     *  创建专题和商品的关联关系
     *  这里属于多对多关系所以使用belongsToMany关联
     * */
    public function products()
    {
        //  参数一：关联的模型名 参数二：关联关系表名
        //  参数三：关联关系表与关联模型的关联外键(theme_product表与Product表的关联外键)
        //  参数四：关联关系表与当前模型的关联外键(theme_product表与theme表的关联外键)
        return $this->belongsToMany('Product','theme_product',
            'product_id','theme_id');
    }

    /**
     *  创建查询主题简要信息的方法（只查询主题相关信息）
     *  调用当前模型以下定义的关联模型的关系执行查询操作
     * */
    public static function getThemeByID($ids)
    {
        $result = self::with('topicImg,headImg')
            ->select($ids);
        return $result;
    }

    /**
     *  创建Theme表和Image表的topic图关联关系
     *  这里属于一对一的关系所以使用belongsTo关联关系
     */
    public function topicImg()
    {
        return $this->belongsTo('Image','topic_img_id','id');
    }

    /**
     *  创建Theme表和Image表的head图关联关系
     *  这里属于一对一的关系所以使用belongsTo关联关系
     */
    public function headImg()
    {
        return $this->belongsTo('Image','head_img_id','id');
    }
}