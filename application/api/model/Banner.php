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

    //  自定义当前模型在返回数据时，需要隐藏的字段信息
    //  只要设置了hidden属性这里返回的所有字段在返回给控制器时，都将被隐藏。
    protected $hidden = ['delete_time','update_time'];

    //  也可以通过在模型中设置允许返回的字段来实现字段隐藏功能.
    //  protected $visible = [];

    /**
     *  根据BannerID获取对应的Banner信息
     *
     * @param Int $id 对应Banner_item的主键ID
     * @return Array 输出当前Banner位置下的所有Banner信息
     * @throws Exception
     */
    public static function getBannerByID($id)
    {
        //  嵌套关联关系
        //  BannerModel不仅要关联items关联关系也要关联items下的img关联关系
        //  $banner = BannerModel::with(['items','items.img'])->find($id);
        $banner = self::with(['items','items.img'])->find($id);
        return $banner;
        //        //  原生SQL的方法
        //        //  $result = Db::query('select * from banner_item where banner_id =?',[$id]);
        //        //  Query构造器的方法
        //        $result = Db::table('banner_item')->where('banner_id','=',$id)->select();
        //        //  Query构造器下的闭包写法
        //        $result = Db::table('banner_item')->where(function($query) use ($id){
        //            $query->where('id','=',$id)->whereOr('img_id','=',65);
        //        })->whereOr(function($query){
        //            $query->where('type','<>',3)->whereOr('banner_id','=',1);
        //        })->select();
        //        //   "SELECT * FROM `banner_item` WHERE  (  `id` = 1 OR `img_id` = 65 ) OR (  `type` <> 3 OR `banner_id` = 1 )"
        //        //  获取上一个执行的Sql。
        //        //$result = Db::table('banner_item')->getLastSql();
        //        return $result;

    }


    /**
     *  当前模型与banner_items的模型关联关系
     *
     * @return 二维数组 输出当前Banner位置下的所有Banner信息
     */
    public function items()
    {
        //  一对多关联模型使用hasMany方法
        // 参数一关联表对应的模型名 参数二当前表的外键名 参数三关联表的主键名
        return $this->hasMany('BannerItem','banner_id','id');

    }


}