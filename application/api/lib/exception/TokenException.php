<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/22 18:00
 * 描  述:
 */

namespace app\api\lib\exception;


class TokenException extends BaseException
{
    public $code = 401;
    public $msg  = 'Token已过期或无效的Token';
    public $errorCode = 10001;
}