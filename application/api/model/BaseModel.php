<?php

namespace app\api\model;

use think\Model;

class BaseModel extends Model
{
    //  将读取器的操作修改为一个私有的成员方法该方法的命名不遵循读取器的要求
    protected function prefixImgUrl($value,$data)
    {
        $finalUrl = $value;
        //  如果当前数据表的相关字段的from=1即为本地存储则需要进行Url拼接
        if($data['from'] == 1)
        {
            //  利用读取器和自定义配置参数进行数据拼接
            $finalUrl = config('setting.img_prefix').$finalUrl;
        }
        //  否则直接返回Url 以及否则将拼接好的Url进行返回。
        return $finalUrl;
    }
}
