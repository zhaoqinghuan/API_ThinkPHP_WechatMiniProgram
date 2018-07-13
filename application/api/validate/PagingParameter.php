<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/12 16:25
 * 描  述:
 */

namespace app\api\validate;


class PagingParameter extends BaseValidate
{
    //  定义需要验证的参数 以及验证规则
    protected $rule = [
      'page'    =>  'isPositiveInteger',
      'size'    =>  'isPositiveInteger',
    ];

    //  定义验证失败的返回信息
    protected $message = [
        'page'    =>  '分页参数必须是正整数',
        'size'    =>  '分页参数必须是正整数',
    ];
}