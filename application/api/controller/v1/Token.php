<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/19 17:49
 * 描  述:
 */

namespace app\api\controller\v1;


use app\api\lib\exception\ParameterException;
use app\api\service\BaseService;
use app\api\service\UserToken;
use app\api\validate\TokenGet;

class Token
{
    /**
     *  令牌校验方法
     *  @params
     *      $token   { String }  客户端提交的待检验令牌
     */
    public function verifyToken($token=''){
        //  对Token进行校验，直接校验不写验证器
        if(!$token){
            throw new ParameterException([
                'Token不能为空'
            ]);
        }
        //  调用Service层的检测token的方法对Token进行检测
        $valid = BaseService::verifyToken($token);
        //  返回结果
        return [
            'isValid' => $valid
        ];
    }

    /**
     *  创建获取令牌的方法
     *  @params      $code integer
     *  @url        /token/user
     *  @http       POST
     *  @return     $userToken
     */
    public function getToken($code='')
    {
        //  参数校验
        (new TokenGet())->goCheck();
        //  实例化服务层中的userToken类
        $ut = new UserToken($code);
        //  调用userToken类下的get方法
        $userToken = $ut->get();
        //  将获取到的加密后的token返回给客户端
        //  因为这里是返回给接口的数据因此将这里的数据修改为数组形式框架会自动帮我们将其转换为Json形式
        return [
            'token' => $userToken
        ];
    }

}