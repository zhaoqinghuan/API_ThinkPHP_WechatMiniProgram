<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/19 18:12
 * 描  述:
 */

namespace app\api\model;


class User extends BaseModel
{
    //  根据openid查询用户相关信息
    public static function getByOpenID($openid)
    {
        //$user = self::where('openid','=','openid')->find();
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }
}