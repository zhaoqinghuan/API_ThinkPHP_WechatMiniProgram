<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/11 16:06
 * 描  述:
 */

namespace app\api\controller\v1;

use app\api\lib\exception\ThemeException;
use app\api\validate\IDCollection;
use app\api\model\Theme as ThemeModel;
use app\api\validate\IDMustBePostiveInt;

class Theme
{
    /**
     *  创建根据主题ID获取相关商品的方法
     *  @url    /theme/:id
     *  @http   GET
     *  @id     对应的主题id
     */
    public function getComplexOne($id)
    {
        //  对参数进行正整数校验
        (new IDMustBePostiveInt())->goCheck();
        //  直接调用模型文件中的获取数据的方法
        $theme = ThemeModel::getThemeWithProducts($id);
        //  判断是否能够正常获取数据，
        if(!$theme){
            throw new ThemeException();
        }
        return $theme;
    }

    /**
     * 创建获取首页主题的方法
     * @url         /theme?ids=id1,id2,id3..
     * @http        GET
     * @id1,id2,id3 对应的需要获取的主题id
     * */
    public function getSimpleList($ids='')
    {
        //  调用验证器进行验证
        (new IDCollection())->goCheck();
        //  将参数字符串转化为数组
        $ids = explode(',',$ids);
        //  调用模型进行模型关联并查询相关数据
        $result = ThemeModel::getThemeByID($ids);
        //  如果出现异常调用异常处理类
        //if(!$result){
        //  修改错误判断条件为框架支持的判断数据集是否为空的方法
        if($result->isEmpty()){
            throw new ThemeException();
        }
        return $result;
    }
}