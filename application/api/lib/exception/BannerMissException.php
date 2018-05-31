<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/31 11:30
 * 描  述:
 */

namespace app\api\lib\exception;

//  Banner不存在异常返回信息
class BannerMissException extends BaseException
{
    //  重写异常处理返回信息中的参数
    public $code = 404;
    public $msg = '请求的Banner不存在';
    public $errorCode = 40000;
}