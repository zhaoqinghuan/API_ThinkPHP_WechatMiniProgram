<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/5/30 10:53
 * 描  述:
 */

namespace app\api\controller\v1;
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
        echo '123456';
    }
}