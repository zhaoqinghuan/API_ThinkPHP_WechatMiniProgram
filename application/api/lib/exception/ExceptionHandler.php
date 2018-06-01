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
use think\Log;
use think\Request;
use think\Config;
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
            //  $switch = true;//  自定义一个返回值格式的变量
            $switch = Config::get('app_debug');
            //   考虑到通用性这里直接将APP_DEBUG变量作为这个开关的值
            if($switch){//  如果变量为true则返回错误页面
                return parent::render($e);
                //  异常处理类处理的方法是通过重写父类的render方法实现的参数传出
                //  因此在这里只需要在子类中重新调用父类中的render方法就可以实现返回错误页面
            }else{//    如果变量为false则返回json字符串
                //  服务器自身产生的异常
                $this->code = 500;
                $this->msg = '服务器错误';
                $this->errorCode = 99999;
                //  调用自定义的日志写入方法
                $this->recodeErrorLog($e);
            }
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
    //  自定义异常日志写入的方法
    private function recodeErrorLog(Exception $e)
    {
        //  在使用日志记录的地方重新修改配置项使系统进行日志记录
        Log::init([
            'type' => 'File',
            'path' => LOG_PATH,
            'level' => ['error']
        ]);
        //调用日志记录类进行日志写入
        Log::record($e->getMessage(),'error');
    }
}