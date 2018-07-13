<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/7/3 16:01
 * 描  述:
 */

namespace app\api\model;


class Order extends BaseModel
{
    //  设置订单表的隐藏字段
    protected $hidden = ['user_id','delete_time','update_time'];

    //  设置当前模型对应数据表的时间戳数据框架自动维护
    protected $autoWriteTimestamp = true;

//    //  重定义自动维护的创建时间的字段名
//    protected $createTime = 'XXXXXXX';
//    //  重定义自动维护的更新时间的字段名
//    protected $updateTime = 'XXXXXXX';
//    //  重定义自动维护的删除时间的字段名
//    protected $deleteTime = 'XXXXXXX';

    //  定义snap_items字段对应的读取器
    public function getSnapItemsAttr($value)
    {
        //  读取器自身接收原始数据
        //  首先对原始数据进行判空
        if(empty($value)){
            //  如果为空 直接返回null
            return null;
        }
        //  将json字符串转换成json对象并返回
        return json_decode($value);
    }

    //  定义snap_address字段对应的读取器
    public function getSnapAddressAttr($value)
    {
        //  读取器自身接收原始数据
        //  首先对原始数据进行判空
        if(empty($value)){
            //  如果为空 直接返回null
            return null;
        }
        //  将json字符串转换成json对象并返回
        return json_decode($value);
    }

    //  定义方法根据当前UID获取用户对应的订单简要信息
    public static function getSummaryByUser($uid,$page,$size)
    {
        //  查询结果返回结构为对象
        $pagingData = self::where('user_id','=',$uid)
             ->order('create_time desc')
             // 使用分页查询并携带分页参数 第一个参数表示每页显示记录数，
             // 第二个表示是否启用简洁模式(不查询总记录数，有效缓解Mysql查询压力，)
             // 第三个参数表示当前页数。
            ->paginate($size,true,['page'=>$page]);
        return $pagingData;
    }

}