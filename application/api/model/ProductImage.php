<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/25 11:35
 * 描  述:
 */

namespace app\api\model;


class ProductImage extends BaseModel
{
    //  定义当前模型需要隐藏的字段名
    protected $hidden = ['img_id','delete_time','product_id'];

    /**
     *  定义商品图片信息表与图片信息表之间的关联关系
     *  一对一关联关系所以使用belongsTo
     *  参数一:关联模型文件  参数二:当前表关联到关联模型文件的外键 参数三:关联模型文件的主键
     * */
    public function imgUrl()
    {
        return $this->belongsTo('Image','img_id','id');
    }

}