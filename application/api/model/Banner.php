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

//  引入Model类
use think\Model;
//  继承Model类
class Banner extends Model
{

    //  重写类对象下的方法指定当前模型类对应的数据表，
    //  如果模型名和数据表名一致则无需手动指定。
    protected $table = 'banner';

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
        //  原生SQL的方法
        //  $result = Db::query('select * from banner_item where banner_id =?',[$id]);
        //  Query构造器的方法
        $result = Db::table('banner_item')->where('banner_id','=',$id)->select();
        //  Query构造器下的闭包写法
        $result = Db::table('banner_item')->where(function($query) use ($id){
            $query->where('id','=',$id)->whereOr('img_id','=',65);
        })->whereOr(function($query){
            $query->where('type','<>',3)->whereOr('banner_id','=',1);
        })->select();
        //   "SELECT * FROM `banner_item` WHERE  (  `id` = 1 OR `img_id` = 65 ) OR (  `type` <> 3 OR `banner_id` = 1 )"
        //  获取上一个执行的Sql。
        //$result = Db::table('banner_item')->getLastSql();
        return $result;
    }
}