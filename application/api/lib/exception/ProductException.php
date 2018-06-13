<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/13 14:58
 * 描  述:
 */

namespace app\api\lib\exception;


class ProductException extends BaseException
{
    //  重写异常处理返回信息中的参数
    public $code = 404;
    public $msg = '指定的商品不存在,请检查参数';
    public $errorCode = 20000;
}