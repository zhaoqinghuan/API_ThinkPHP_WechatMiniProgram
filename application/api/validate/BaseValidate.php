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
    /**
     *  自定义验证手机号码的验证规则
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  根据验证规则获取客户端传递过来的参数
     *  @param  $arrays  array 客户端传递过来的所有参数
     *  @return $newArray array 按照过滤规则过滤后的参数
     */
    public function getDataByRule($arrays)
    {
        //  判断参数中是否包含user_id或uid
        if(array_key_exists('user_id',$arrays) || array_key_exists('uid',$arrays))
        {
            //  这里直接过滤掉客户端传递过来的所有包含user_id或uid的参数，
            //  这两个参数只能通过令牌获取。
            throw new ParameterException([
                'msg' => '参数中包含非法参数user_id或uid'
            ]);
        }
        //  定义一个新数组用来存储验证通过后的结果
        $newArray = [];
        //  根据验证规则获取数据，只将符合规则的参数存储到数据库中
        foreach ($this->rule as $key=>$value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }


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
    /**
     * 自定义参数不能为空验证规则
     * @field 需要进行校验的字段名
     * @value 需要进行校验的字段值
     * @rule  需要进行校验的验证规则
     * @data  数据集合
     */
    protected function isNotEmpty($value,$rule='',$data='',$field='')
    {
        if(empty($value)){
            //  判断如果当前字段不为空
            return false;
        }
        else{
            return true;
        }
    }


    /**
     * 自定义正整数验证规则
     * @field 需要进行校验的字段名
     * @value 需要进行校验的字段值
     * @rule  需要进行校验的验证规则
     * @data  数据集合
     */
    protected function isPositiveInteger($value,$rule='',$data='',$field='')
    {
        if(is_numeric($value) && is_int($value+0) && ($value+0)>0){
            //  判断是不是一个数字     判断是不是整型  判断是否大于0
            return true;
        }
        else{
            return false;
            //  验证失败返回该字段不符合验证要求
            //return $field.'字段不符合验证要求';
        }
    }

}
