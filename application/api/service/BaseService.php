<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/20 16:44
 * 描  述:
 */

namespace app\api\service;


class BaseService
{
    /**
     *  自定义生成token的方法
     *  为了防止被别人伪造字符串这里使用三组字符串进行MD5加密
     */
    public static function generateToken()
    {
        //  1.选择32位无意义字符组成随机字符串
        $randChars = self::getRandChar(32);
        //  2.系统请求时候的时间戳
        $timestamp = $_SERVER['REQUEST_TIME_FLOAT'];
        //  3.系统解密盐
        $salt =config('secure.token_salt');
        //  加密并返回字符串
        return md5($randChars.$timestamp.$salt);
    }

    /**
     *  根据参数生成随机位数的字符串
     *  @param $length integer  需要的字符串位数
     *  @return str string      返回的字符串结果
     */
    public static function getRandChar($length){
        $str = null;
        $strPol = "ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz";
        $max = strlen($strPol)-1;
        for ($i=0;$i<$length;$i++){
            $str .= $strPol[rand(0,$max)];
        }
        return $str;
    }

    /**
     *  模拟get请求发送方法
     *  @param string   $url        get请求地址
     *  @param int      $httpCode   返回状态码
     *  @return mixed
     */
    function curl_get($url,&$httpCode = 0){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);

        //不做证书校验，部署在linux环境下请改位true
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
        $file_contents = curl_exec($ch);
        $httpCode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        curl_close($ch);
        return $file_contents;
    }

    /**
     *  模拟post请求发送方法
     *  @param string   $url        get请求地址
     *  @param array    $params     返回状态码
     *  @return mixed
     */
    function curl_post($url,array $params = array()){
        $data_string = json_encode($params);
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch,CURLOPT_HTTPHEADER,
            array(
                'Content-Type: application/json'
            )
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);
    }

    function curl_post_raw($url,$rawData){
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_HEADER,0);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,10);
        curl_setopt($ch,CURLOPT_POST,1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,false);
        curl_setopt($ch,CURLOPT_POSTFIELDS,$rawData);
        curl_setopt($ch,CURLOPT_HTTPHEADER,
            array(
                'Content-Type: text'
            )
        );
        $data = curl_exec($ch);
        curl_close($ch);
        return ($data);
    }
}