<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/31 8:54
 * 描  述:
 */

namespace app\api\model;


use think\Db;
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
        $result = Db::query('select * from banner_item where banner_id =?',[$id]);
        return $result;
    }
}