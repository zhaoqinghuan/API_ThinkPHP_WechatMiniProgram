<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/30 15:10
 * 描  述:
 */

namespace app\api\validate;


//  ID参数的验证器
class IDMustBePostiveInt extends BaseValidate
{
    protected $rule =[
        //  自定义的验证规则直接在这里调用使用即可
      'id' => 'require|isPositiveInteger'
    ];

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
            //  验证失败返回该字段不符合验证要求
            return $field.'字段不符合验证要求';
        }
    }
}