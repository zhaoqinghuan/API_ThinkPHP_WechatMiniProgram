<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/26 15:46
 * 描  述:
 */

namespace app\api\lib\exception;


class SuccessMessage
{
    //  这个操作结果处理可以不继承BaseException

    public $code = 201;
    public $msg  = '操作成功';
    public $errorCode = 00000;
}