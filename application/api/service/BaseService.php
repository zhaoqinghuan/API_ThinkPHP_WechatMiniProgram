<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/20 16:44
 * 描  述:
 */

namespace app\api\service;
use app\api\lib\enum\ScopeEnum;
use app\api\lib\exception\ForbiddenException;
use app\api\lib\exception\TokenException;
use think\Cache;
use think\Exception;
use think\Request;

class BaseService
{
    /**
     *  自定义检测Token是否有效
     */
    public static function verifyToken($token){
        $exist = Cache::get($token);    //  从缓存中获取是否存在一个名为Token的缓存
        if($exist){
            return true;                //  存在返回true
        }else{
            return false;               //  否则返回False
        }
    }

    /**
     *  检测当前发起操作的用户和当前数据的创建用户是否是同一个人
     */
    public static function isValidOperate($checkedUID)
    {
        //  对参数进行判空
        if(!$checkedUID){
            throw new Exception('被检测的用户UID不能为空');
        }
        //  获取当前请求发起人的UID
        $currentOperateUID = self::getCurrentUid();
        //  对当前请求的UID和传递过来的UID进行对比
        if($currentOperateUID == $checkedUID){
            return true;
        }
        return false;
    }

        /**
     *  自定义权限管理前置操作的方法封装到Service
     *  只有用户能访问的权限
     */
    public static function needExclusiveScope()
    {
        //  使用Service层中的getCurrentTokenVar方法获取缓存中当前用户的scope值
        $scope = self::getCurrentTokenVar('scope');
        //  先判断scope是否存在
        if($scope){
            //  此接口只能由用户来执行调用，因此这里的权级只能等于UserScope
            if($scope == ScopeEnum::UserScope){
                return true;
            }else{
                //  抛出权级无法进行此操作异常
                throw new ForbiddenException();
            }
        }else{
            //  如果Scope不存在说明当前客户端可能Token已过期，抛出Token过期异常
            throw new TokenException();
        }
    }

    /**
     *  自定义权限管理前置操作的方法封装到Service
     *  用户和管理员都能访问的权限
     */
    public static function needPrimaryScope()
    {
        //  使用Service层中的getCurrentTokenVar方法获取缓存中当前用户的scope值
        $scope = self::getCurrentTokenVar('scope');
        //  先判断scope是否存在
        if($scope){
            //  此接口只能由用户或管理员来执行调用，因此这里的权级只能大于等于UserScope
            if($scope >= ScopeEnum::UserScope){
                return true;
            }else{
                //  抛出权级无法进行此操作异常
                throw new ForbiddenException();
            }
        }else{
            //  如果Scope不存在说明当前客户端可能Token已过期，抛出Token过期异常
            throw new TokenException();
        }
    }

    /**
     *  通用的根据Token兑换存储在缓存中数据的方法
     *  需要传递参数根据参数返回对应的数据
     */
    public static function getCurrentTokenVar($key)
    {
        //  规定所有的Token只能通过请求的Http头信息中获取
        //  使用Request下的获取请求头信息的方法来获取参数
        $token = Request::instance()
            ->header('token');
        //  调用缓存的get方法获取缓存
        $vars = Cache::get($token);
        //  对结果进行判断，有可能因为缓存失效而无法获取数据
        if(!$vars){
            //  如果不存在直接调用Token异常处理类进行异常回调
            //  默认值Token已过期或无效符合当前需要，因此无需重写回调信息
            throw new TokenException();
        }else{
            //  结果可能本身就是数组（Redis缓存）因此这里多进行一次判断
            if(!is_array($vars)){
                //  将缓存中的结果反序列化成数组
                $vars = json_decode($vars,true);
            }
            //  根据提交的参数key来提取缓存中对应的值
            if(array_key_exists($key,$vars)){
                //  根据提交的KEY返回对应的值
                return $vars[$key];
            }else{
                //  否则做抛出异常处理 这里无需
                throw new Exception('尝试获取的Token变量不存在');
            }
        }
    }

    /**
     *  自定义根据UID获取TOKEN的方法
     */
    public static function getCurrentUid()
    {
        $uid = self::getCurrentTokenVar('uid');
        return $uid;
    }

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