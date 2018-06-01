<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/30 15:47
 * 描  述:
 */

namespace app\api\validate;

use app\api\lib\exception\ParameterException;
use think\Validate;
use think\Request;

class BaseValidate extends Validate
{
    //  公用的公共验证器校验方法
    public function goCheck()
    {
        //  获取所有的http请求过来的参数
        $request = Request::instance();
        $params  = $request->param();

        //  因为现在已经属于Validate类的子类中所以无需实例化父类可以直接调用check()方法
        $result  = $this->batch()->check($params);
        if(!$result){
            //获取自定义的通用参数异常错误
            $e = new ParameterException([
                //  在这里我们通过类对象的构造方法解决这个问题
                'msg' => $this->error
//                'msg' => '你传递的参数不对哦，请重新传入',
//                'code' => 404
            ]);
            //通常直接使用类对象赋值的方式也能实现异常处理参数传入
            //$e->msg = $this->error;
            throw $e;
//            如果请求不通过 直接调用Validate类的获取错误方法
//            $error = $this->error;
//            throw new Exception($error);
        }else{
            return true;
        }
    }
}
