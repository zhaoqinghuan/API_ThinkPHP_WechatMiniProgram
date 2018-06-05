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

        //  静态方法调用Banner模型下内置的Get方法获取对应的数据
        $banner = BannerModel::get($id);

        //  模型实例化对象调用方法
        $banner = new BannerModel();
        $banner = $banner->get($id);

        //$banner = BannerModel::getBannerByID($id);
        if(!$banner){
            throw new BannerMissException();
        }
        //  因为BannerModel调用方法后返回的是一个模型对象所以这里无需再进行json转化可直接抛出
        return $banner;
        //return json($banner);

    }
}