<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/13 14:30
 * 描  述:
 */

namespace app\api\validate;


class CountValidate extends BaseValidate
{
    //  自定义验证规则
    protected $rule = [
        //  验证规则1必须是正整数 规则2使用框架提供的默认验证规则验证参数所处的区间
        'count' =>'isPositiveInteger|between:1,15'
    ];
}