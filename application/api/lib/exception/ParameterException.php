<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/31 17:14
 * 描  述:
 */

namespace app\api\lib\exception;

    //  客户端传递参数异常返回信息标准定义
class ParameterException extends BaseException
{
    //  重写异常处理返回信息中的参数
    public $code = 400;
    public $msg = '参数错误';
    public $errorCode = 10000;
}