<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/20 15:01
 * 描  述:  微信相关自定义配置文件
 */
return [
    //  小程序注册时的app_id
    'app_id' => 'xxxx',
    //  小程序注册时的app_secret
    'app_secret' => 'xxxx',
    //  配置置换Code兑换open_id和session_key的Url路径
    //  这里有三个参数需要填入这里先用%s占位。
    'login_url' => "https://api.weixin.qq.com/sns/jscode2session?".
        "appid=%s&secret=%s&js_code=%s&grant_type=authorization_code",
    //  自定义微信支付的回调地址
    'pay_back_url' => 'http://api.huiqinsoft.com/api/v1/pay/notify',
];