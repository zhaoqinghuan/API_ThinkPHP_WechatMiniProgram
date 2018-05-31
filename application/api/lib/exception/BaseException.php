<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/31 11:22
 * 描  述:
 */

namespace app\api\lib\exception;

use think\Exception;

/**
 * RestFul API 返回值标准定义类
 * */
class BaseException extends Exception
{
    //让标准类时继承自框架Exception基类这样在控制器中使用子类标准才有效

    //  HTTP状态码 ：500,404,200,
    public $code = 400;
    //  返回错误具体信息
    public $msg = '参数错误';
    //  自定义错误码 ：10001
    public $errorCode = 10000;
}