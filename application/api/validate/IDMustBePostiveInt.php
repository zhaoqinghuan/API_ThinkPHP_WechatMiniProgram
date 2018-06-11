<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/30 15:10
 * 描  述:
 */

namespace app\api\validate;


//  ID参数的验证器
class IDMustBePostiveInt extends BaseValidate
{
    protected $rule =[
        //  自定义的验证规则直接在这里调用使用即可
      'id' => 'require|isPositiveInteger'
    ];

    protected $message=[
        'id' => 'id字段必须是一个正整数！'
    ];
}