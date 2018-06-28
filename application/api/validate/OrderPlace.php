<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/28 15:38
 * 描  述:
 */

namespace app\api\validate;


class OrderPlace extends BaseValidate
{
    protected $rule = [
        'products' => 'checkProducts'
    ];


}