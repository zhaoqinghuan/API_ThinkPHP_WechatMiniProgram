<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/3 16:01
 * 描  述:
 */

namespace app\api\model;


class Order extends BaseModel
{
    //  设置订单表的隐藏字段
    protected $hidden = ['user_id','delete_time','update_time'];

    //  设置当前模型对应数据表的时间戳数据框架自动维护
    protected $autoWriteTimestamp = true;

//    //  重定义自动维护的创建时间的字段名
//    protected $createTime = 'XXXXXXX';
//    //  重定义自动维护的更新时间的字段名
//    protected $updateTime = 'XXXXXXX';
//    //  重定义自动维护的删除时间的字段名
//    protected $deleteTime = 'XXXXXXX';

}