<?php

namespace app\api\model;

use think\Model;

class BannerItem extends Model
{
    //  自定义当前模型文件需要隐藏的字段
    protected $hidden = ['delete_time','update_time','id','banner_id','img_id',''];
    /**
     * 创建Banner_Item 和 Image的模型关联关系
     */
    public function img()
    {
        //  Image表的信息和Banner_Item的关联关系属于一对一
        //  参数一关联表对应的模型名 参数二当前表的外键名 参数三关联表的主键名
        return $this->belongsTo('Image','img_id','id');
    }
}
