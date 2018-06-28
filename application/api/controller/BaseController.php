<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/28 15:05
 * 描  述:
 */

namespace app\api\controller;


use think\Controller;

use app\api\service\UserToken as TokenService;
class BaseController extends Controller
{
    /**
     *  继承自Service层的前置操作方法
     *  只有用户和管理员拥有调用权限
     */
    protected function checkPrimaryScope()
    {
        TokenService::needPrimaryScope();
    }

    /**
     *  继承自Service层的前置操作方法
     *  只有用户拥有调用权限
     */
    protected function checkExclusiveScope()
    {
        TokenService::needExclusiveScope();
    }
}