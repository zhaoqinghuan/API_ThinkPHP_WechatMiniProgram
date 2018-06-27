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
    /**
     *  建立User表和UserAddress表的关联关系
     *  这里的关系仍然是一对一关系但是外键关系在UserAddress表中，因此需要使用hasOne关系
     *  一对一关系使用hasOne还是belongsTo关键看外键设置在那个表，设置在当前表用belongsTo，在关联表用hasOne
     */
    public function address()
    {
        return $this->hasOne('UserAddress','user_id','id');
    }

    //  根据openid查询用户相关信息
    public static function getByOpenID($openid)
    {
        //$user = self::where('openid','=','openid')->find();
        $user = self::where('openid','=',$openid)->find();
        return $user;
    }
}