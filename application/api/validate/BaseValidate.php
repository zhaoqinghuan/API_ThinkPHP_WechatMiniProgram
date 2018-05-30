<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/30 15:47
 * 描  述:
 */

namespace app\api\validate;

use think\Validate;
use think\Request;
use think\Exception;

class BaseValidate extends Validate
{
    //  公用的公共验证器校验方法
    public function goCheck()
    {
        //  获取所有的http请求过来的参数
        $request = Request::instance();
        $params  = $request->param();

        //  因为现在已经属于Validate类的子类中所以无需实例化父类可以直接调用check()方法
        $result  = $this->check($params);
        if(!$result){
            //  如果请求不通过 直接调用Validate类的获取错误方法
            $error = $this->error;
            throw new Exception($error);
        }else{
            return true;
        }
    }
}
