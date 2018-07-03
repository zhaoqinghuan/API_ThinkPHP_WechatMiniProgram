<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/29 11:28
 * 描  述:
 */

namespace app\api\lib\exception;



class OrderException extends BaseException
{
    public $code = 404;
    public $msg  = '订单不存在，请检查ID';
    public $errorCode = 80000;
}