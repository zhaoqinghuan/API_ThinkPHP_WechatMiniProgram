<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/26 16:42
 * 描  述:
 */

namespace app\api\model;


class UserAddress extends BaseModel
{
    //  自定义需要隐藏的字段
    protected $hidden = [
        'id', 'delete_time', 'user_id'
    ];

}