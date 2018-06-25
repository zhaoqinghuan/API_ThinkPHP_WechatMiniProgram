<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/25 11:39
 * 描  述:
 */

namespace app\api\model;


class ProductProperty extends BaseModel
{
    //  自定义当前模型需要隐藏的字段名
    protected $hidden = ['product_id','delete_time','id'];

}