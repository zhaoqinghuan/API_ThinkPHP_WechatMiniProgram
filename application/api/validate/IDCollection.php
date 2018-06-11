<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/11 17:19
 * 描  述:
 */
namespace app\api\validate;
class IDCollection extends BaseValidate
{
    //  定义规则
    protected $rule = [
        //需要验证传入的参数是一个以逗号分隔的字符串框架不提供这种验证方法,自定义一个验证规则CheckIDs
      'ids' => 'require|CheckIDs'
    ];
    //  定义验证失败后的回调信息
    protected $message = [
        'ids' => 'ids参数必须是一个以逗号分隔的多个正整数'
    ];
    /**
     *  创建自定义验证规则CheckIDs用于验证参数是一个用‘,’分割的字符
     * @value       就是客户端传过来的id1,id2,id3...
     */
    protected function CheckIDs($value)
    {
        //  先把字符串转换成数组
        $values = explode(',',$value);
        //  判断数组是否为空
        if(empty($values)){
            return false;
        }
        //  确定每一个ID都是一个正整数
        foreach ($values as $value){
            /*
             * 这个判断每一个ID都是正整数的方法在IDMustBePostveInt中有创建
             * 为了保持其扩展性，将其剪切到BaseValidate中因为这是一个私有属性，
             * 可以直接在子类中被调用，因此直接在当前方法中调用
             * */
            if(!$this->isPositiveInteger($value)){
                return false;
            }
        }
        return true;
    }
}