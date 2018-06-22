<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/20 17:41
 * 描  述:
 */

namespace app\api\lib\exception;


class WeChatException extends BaseException
{
    public $code = 400;
    public $msg  = '微信服务器接口调用失败';
    public $errorCode = 999999;
}