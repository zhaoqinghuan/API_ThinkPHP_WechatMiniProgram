<?php

namespace app\api\model;

use think\Model;

class Image extends Model
{
    //  隐藏image表中通用的不需要返回给客户端的字段
    protected $hidden = ['id','from','delete_time','update_time'];
}
