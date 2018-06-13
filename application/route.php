<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\Route;

// 标准TP5路由定义方法
//Route::请求方式('路由名/:参数','模块名/控制器名/方法名');

//banner部分相关接口
//Route::get('banner/:id','api/v1.Banner/getBanner');
//将URL修改为标准的RestFulAPI接口
//Route::get('api/v1/banner/:id','api/v1.Banner/getBanner');

//  获取首页轮播图接口
Route::get('api/:version/banner/:id','api/:version.Banner/getBanner');
//  根据主题ID获取主题下的相关商品信息
//  这个接口的设计理念就是RestFul的标准，当接口后带有参数就是获取某个ID对应接口的详细信息
Route::get('api/:version/theme/:id','api/:version.Theme/getComplexOne');
//  获取首页主题接口
Route::get('api/:version/theme','api/:version.Theme/getSimpleList');
