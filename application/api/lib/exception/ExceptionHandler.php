<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/31 11:20
 * 描  述:
 */
namespace app\api\lib\exception;
use Exception;
use think\exception\Handle;
use think\Request;
//  自定义全局异常处理类的基类
class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $errorCode;
    //重写异常处理方法
    public function render(Exception $e)//  所有代码中抛出的异常都会经过render方法来渲染并返回
    {
        //判断当前的错误信息是否是来自错误信息基类
        if($e instanceof BaseException){
            //  处理用户输入错误所产生的异常
            //  将重写过BaseException的错误信息重写给当前异常处理类的私有属性里
            $this->code = $e->code;
            $this->msg = $e->msg;
            $this->errorCode = $e->errorCode;
        }else{
            //  服务器自身产生的异常
            $this->code = 500;
            $this->msg = '服务器错误';
            $this->errorCode = 99999;
        }
        //获取当前请求的URL路径
        $request = Request::instance();

        //构建返回信息的数组
        $result =[
          'msg' => $this->msg,
          'errorCode' => $this->errorCode,
          'request_url' => $request->url()
        ];
        return json($result,$this->code);
    }
}