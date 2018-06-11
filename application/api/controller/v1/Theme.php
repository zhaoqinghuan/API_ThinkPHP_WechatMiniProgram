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
//  引入模型类
use app\api\model\Theme as ThemeModel;
class Theme
{
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
        if(!$result){
            throw new ThemeException();
        }
        return $result;
    }
}