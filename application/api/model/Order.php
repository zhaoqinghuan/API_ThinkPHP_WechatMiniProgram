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
}