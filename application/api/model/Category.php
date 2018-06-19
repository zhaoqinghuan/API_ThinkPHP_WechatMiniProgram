<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/13 17:13
 * 描  述:
 */

namespace app\api\model;


class Category extends BaseModel
{
    /**
     *  自定义当前表需要隐藏的相关字段
     * */
    protected $hidden = [
        'update_time',
        'delete_time',
        'create_time'
    ];

    /**
     *  创建当前模型与Image模型的关联关系
     *  这里属于一对一关系所以使用belongsTo关联
     * */
    public function img()
    {
        //  一对一关联关系，参数一：对应模型名，参数二：当前模型外键ID，参数三：关联表的主键ID
        return $this->belongsTo('Image','topic_img_id','id');
    }

    /**
     *  创建调用关联模型执行查询所有分类信息操作的静态方法
     */
    public static function getAllCategoriesInfo()
    {
        //  all([],'关联方法');方法就相当于with('关联方法')->select();
        $Categories = self::all([],'img');
        return $Categories;
    }
}