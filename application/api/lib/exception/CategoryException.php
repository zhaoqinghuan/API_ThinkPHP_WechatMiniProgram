<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/15 9:59
 * 描  述:
 */

namespace app\api\lib\exception;


class CategoryException extends BaseException
{
    //  自定义错误提示信息
    public $code = 404;
    public $msg = '指定的类目不存在,请检查参数';
    public $errorCode = 50000;
}