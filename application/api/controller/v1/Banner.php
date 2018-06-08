<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/30 10:53
 * 描  述:
 */

namespace app\api\controller\v1;
use app\api\lib\exception\BannerMissException;
use app\api\model\Banner as BannerModel;
use app\api\validate\IDMustBePostiveInt;

class Banner
{
    /**
     * 获取指定Banner类型下的所有Banner信息
     * @url   /banner/:id
     * @http  GET
     * @id    Banner类型id(如1=小程序首页Banner)
     */
    public function getBanner($id)
    {
        (new IDMustBePostiveInt())->goCheck();
//          静态方法调用Banner模型下内置的Get方法获取对应的数据
//        $banner = BannerModel::get($id);
//
//          1.在静态方法调用时需要加入模型关联关系
//          2.在使用方法时，只能使用Db的find或select方法
//        $banner = BannerModel::with('items')->find($id);
//
//          嵌套关联关系
//          BannerModel不仅要关联items关联关系也要关联items下的img关联关系
//        $banner = BannerModel::with(['items','items.img'])->find($id);
//
//
//          模型实例化对象调用方法
//        $banner = new BannerModel();
//        $banner = $banner->get($id);
        $banner = BannerModel::getBannerByID($id);
//        $data = $banner->toArray();//因为当前Banner模型调用得到的结果是一个对象所以我们想要获取他就要先把他转化成数组
//        unset($data['delete_time']);//  直接调用删除方法删除数组中的数据。

//        $banner->hidden(['update_time','delete_time']); //屏蔽不想返回给客户端的数据。
//        $banner->visible(['id','name','description']);  //只返回想要返回给客户端的数据。

        if(!$banner){
            throw new BannerMissException();
        }
        //调用配置文件中的配置项
        $img_prefix = config('setting.img_prefix');

        //  因为BannerModel调用方法后返回的是一个模型对象所以这里无需再进行json转化可直接抛出
        //return json($banner);
        return $banner;

    }
}
