<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/11 18:43
 * 描  述:
 */

namespace app\api\lib\exception;


class ThemeException extends BaseException
{
    //  HTTP状态码 ：500,404,200,
    public $code = 400;
    //  返回错误具体信息
    public $msg = '指定的主题不存在，请检查主题ID是否合法';
    //  自定义错误码
    public $errorCode = 30000;
}