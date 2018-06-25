<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/19 18:18
 * 描  述:
 */
namespace app\api\service;
use app\api\lib\exception\TokenException;
use app\api\lib\exception\WeChatException;
use think\Exception;
use app\api\model\User as UserModel;
class UserToken extends BaseService
{
    /**
     *  自定义成员属性定义在使用Code置换open_id和App_Secret时候的相关参数
     *  @param  $code           客户端传递过来的Code码
     *  @param  $wxAppId        小程序的AppId
     *  @param  $wxAppSecret    小程序的AppSecret
     *  @param  $wxLoginUrl     小程序的兑换连接地址
     */
    protected $code;
    protected $wxAppId;
    protected $wxAppSecret;
    protected $wxLoginUrl;
    /**
     *  构造函数将wxLoginUrl拼写完整
     *  先从配置文件中将相关信息取出
     */
    function __construct($code)
    {
        //  $this->$code = $code;
        $this->code = $code;
        $this->wxAppId = config('wxSetting.app_id');
        $this->wxAppSecret = config('wxSetting.app_secret');
        //  这里的wxLoginUrl在这里用sprinf函数将url拼写完整
        $this->wxLoginUrl = sprintf(config('wxSetting.login_url'),
            $this->wxAppId,$this->wxAppSecret,$this->code);
    }
    /**
     *  封装调用微信服务器获取token然后将获取到的信息进行存储并加密返回
     */
    public function get()
    {
        $result = $this->curl_get($this->wxLoginUrl);
        //  因为上一步获取到的结果是一个字符串这里用json_decode将其转换为对象
        $wxResult = json_decode($result,true);

        //  对微信获取的结果进行判断可能存在为空的问题
        if(empty($wxResult)){
            //  这里直接使用系统的Exception是因为自定义异常处理会将其返回给客户端，系统的则会将其记录为日志。
            throw new Exception('获取session_key及OpenID时异常，微信内部错误');
        }else{
            //  即使结果不为空也不带表当前正确返回结果
            //  根据经验对结果进行二次判断如果返回结果中存在errCode
            $loginFail = array_key_exists('errcode',$wxResult);
            if($loginFail){
                //  如果存在errCode则说明调用失败， 执行异常处理
                $this->processLoginError($wxResult);
            }else{
                //  否则说明调用成功 进行颁发令牌操作
                return $this->grantToken($wxResult);
            }
        }
    }
    /**
     *  自定义微信接口调用成功后颁发令牌操作
     */
    private function grantToken($wxResult)
    {
        //  1.在wxResult中拿取openid
        $openID = $wxResult['openid'];
        //  2.拿openid到数据库中对比是否已经注册了该用户
        $user = UserModel::getByOpenID($openID);
        if($user){
            //  存在用户获取用户ID
            $uid = $user->id;
        }else{
            //  3.如果不存在新增一条用户信息
            $uid = $this->newUser($openID);
        }
        //  4.生成令牌，准备缓存数据，写入缓存文件
        //  这里写入缓存的数据为数组形式key就是当前用户所携带的令牌
        //  Value主要包括三方面 wxResult(获取到的openID和)，uid（用户信息表主键ID），scope（用户身份）
        $cachedValue = $this->prepareCacheValue($wxResult, $uid);
        //  5.将令牌返回给客户端
        return $token = $this->saveToCache($cachedValue);
    }
    /**
     *  自定义一个写入缓存数据的方法
     */
    private function saveToCache($cachedValue)
    {
        //  缓存信息key
        $key = $this->generateToken();
        //  缓存信息Value
        $value = json_encode($cachedValue);
        //  缓存过期时间
        $expire_in = config('setting.token_expire_in');
        //  调用缓存系统将数据写入缓存
        $request = cache($key,$value,$expire_in);
        //  对缓存结果进行判断
        if(!$request){
            //  如果不存在进行异常处理
            throw new TokenException([
                'msg' => '服务器缓存异常',
                'errorCode' => 10005
            ]);
        }
        //  如果缓存成功返回key
        return $key;
    }

    /**
     *  自定义一个准备缓存数据Value的方法
     */
    private function prepareCacheValue($wxResult, $uid)
    {
        //  封装相关参数到cachedValue中去
        $cachedValue = $wxResult;
        $cachedValue['uid'] = $uid;
        $cachedValue['scope'] = 16;
        return $cachedValue;
    }
    /**
     *  自定义一个新建用户信息的方法
     */
    private function newUser($openID)
    {
        //  直接调用模型下的创建方法新建用户信息
        $user = UserModel::create([
            'openid' => $openID
        ]);
        //  返回当前用户在数据库中的ID号
        return $user->id;
    }

    /**
     *  自定义微信获取结果的异常处理函数
     *  因为此时的wxResult结果中包含有微信返回的错误信息，因此将其返回给客户端是最好的做法。
     */
    private function processLoginError($wxResult)
    {
        //  在客户端实例化该自定义错误信息处理类并对其参数重写
        throw new WeChatException([
            'msg' => $wxResult['errmsg'],
            'errorCode' => $wxResult['errcode']
        ]);
    }
}