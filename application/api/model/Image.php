<?php

namespace app\api\model;


class Image extends BaseModel
{
    //  隐藏image表中通用的不需要返回给客户端的字段
    protected $hidden = ['id','from','delete_time','update_time'];

    //  创建一个读取器
    public function getUrlAttr($value,$data)
    {
        //  当读取器被触发时直接调用基类里的Url处理方法
        return $this->prefixImgUrl($value,$data);
    }
}
