<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/19 17:53
 * 描  述:
 */

namespace app\api\validate;


class TokenGet extends BaseValidate
{
    //  自定义需要检验的参数以及验证条件
    protected $rule = [
      //    这里需要验证code不仅必须传而且不能为空因此需要自定义一个isNotEmpty
      //    自定义验证方法写到基类中增加复用性
      'code' => 'require|isNotEmpty'
    ];

    //  自定义错误返回信息
    protected $message = [
      'code' => 'Code为必选参数',
    ];
}