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

//  获取最新商品接口
Route::get('api/:version/products/recent','api/:version.Product/getRecent');
//  根据分类信息查找对应的商品信息
Route::get('api/:version/products/by_category','api/:version.Product/getAllInCategory');
//  获取某一商品详细信息接口 直接在路由中对参数进行正则判断
Route::get('api/:version/products/:id','api/:version.Product/getOne','[]',['id'=>'\d+']);

//  获取所有分类列表
Route::get('api/:version/category/all','api/:version.Category/getAllCategories');

//  获取令牌接口
//  这里使用post请求方法因为客户端传递的code的特殊性直接放在url中不安全，
Route::post('api/:version/token/user','api/:version.Token/getToken');

//  用户创建或更新地址信息方法
Route::post('api/:version/address','api/:version.Address/createOrUpdateAddress');

//  根据订单ID获取当前订单的详细数据   直接在路由中对参数进行正则判断
Route::get('api/:version/order/:id','api/:version.Order/getDetail',[],['id'=>'\d+']);
//  用户创建订单接口
Route::post('api/:version/order','api/:version.Order/placeOrder');
//  获取当前用户的订单简要信息列表接口
Route::get('api/:version/order/by_user','api/:version.Order/getSummaryByUser');

//  微信支付预订单发起接口
Route::post('api/:version/pay/pre_order','api/:version.Pay/getPreOrder');
//  微信支付支付结果回调接口
Route::post('api/:version/pay/notify','api/:version.Pay/receiveNotify');
//  微信支付支付结果回调调试接口
Route::post('api/:version/pay/re_notify','api/:version.Pay/re_notify');





