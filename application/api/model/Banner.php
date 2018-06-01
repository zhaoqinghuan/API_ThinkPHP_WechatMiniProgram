<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/31 8:54
 * 描  述:
 */

namespace app\api\model;


use think\Exception;

class Banner
{
    /**
     *  根据BannerID获取对应的Banner信息
     * @Tables banner & banner_item
     *
     * @param Int $id 对应Banner_item的主键ID
     * @return Array 输出当前Banner位置下的所有Banner信息
     * @throws Exception
     */
    public static function getBannerByID($id)
    {
//          模拟系统出现的错误
//        try{
//            1 / 0;
//        }
//        catch (Exception $ex){
//            //TODO: 进行异常处理 如：记录日志
//            throw $ex;
//        }
        //  模拟用户输入产生的错误
        //return null;
    }
}