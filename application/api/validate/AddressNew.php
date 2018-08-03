<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/25 15:41
 * 描  述:
 */

namespace app\api\validate;


class AddressNew extends BaseValidate
{
    //  定义验证字段以及验证条件
    protected $rule = [
        'name' => 'require|isNotEmpty',
        'mobile' => 'require',
        'province' => 'require|isNotEmpty',
        'city' => 'require|isNotEmpty',
        'country' => 'require|isNotEmpty',
        'detail' => 'require|isNotEmpty',
    ];
}