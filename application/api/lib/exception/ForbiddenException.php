<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/27 14:24
 * 描  述:
 */

namespace app\api\lib\exception;


class ForbiddenException extends BaseException
{
    public $code = 403;
    public $msg = '权级无法进行此操作';
    public $errorCode = 10002;
}