<?php
/**
 * 作  者:  Icy
 * 联  系:  i@i-qh.cn
 * 时  间:  2018/6/25 15:37
 * 描  述:
 */
namespace app\api\controller\v1;
use app\api\controller\BaseController;
use app\api\lib\enum\ScopeEnum;
use app\api\lib\exception\ForbiddenException;
use app\api\lib\exception\SuccessMessage;
use app\api\lib\exception\TokenException;
use app\api\lib\exception\UserException;
use app\api\model\User as UserModel;
use app\api\model\UserAddress;
use app\api\validate\AddressNew;
use app\api\service\UserToken as TokenService;

class Address extends BaseController
{
    //  自定义前置操作的设置
    protected $beforeActionList = [
        //  前置方法名
        'checkPrimaryScope' => [
            //  需要使用前置方法的方法名
            'only' => 'createOrUpdateAddress,getUserAddress'
        ]
    ];

//    //  重构后的调用Service层的前置验证当前用户身份权级方法
//    protected function checkPrimaryScope()
//    {
//        TokenService::needPrimaryScope();
//    }

//    //  自定义前置方法验证当前用户的身份权级
//    protected function checkPrimaryScope()
//    {
//        //  使用Service层中的getCurrentTokenVar方法获取缓存中当前用户的scope值
//        $scope = TokenService::getCurrentTokenVar('scope');
//        //  先判断scope是否存在
//        if($scope){
//            //  此接可以由用户和管理员来调用，所以这个接口的权级至少要等于用户权级
//            if($scope >= ScopeEnum::UserScope){
//                return true;
//            }else{
//                //  抛出权级无法进行此操作异常
//                throw new ForbiddenException();
//            }
//        }else{
//            //  如果Scope不存在说明当前客户端可能Token已过期，抛出Token过期异常
//            throw new TokenException();
//        }
//    }

    //  获取用户地址信息方法
    public function getUserAddress()
    {
        $uid = TokenService::getCurrentUid();   //  调用Token类下定义的获取根据Token获取用户UID的方法获取用户UID
        $userAddress = UserAddress::where('user_id', $uid)->find();

        if(!$userAddress){
            throw new UserException([
                'msg' => '当前用户地址不存在',
                'errorCode' => 60001
            ]);
        }
        return $userAddress;
    }

    //  创建/更新地址控制器
    public function createOrUpdateAddress()
    {
        //  1.参数校验
        //(new AddressNew())->goCheck();
        $validate = new AddressNew();// 实例化验证类
        $validate->goCheck();// 调用验证类下的验证方法
        //  2.根据TOKEN获取用户的UID
        $uid = TokenService::getCurrentUid();
        //  3.根据UID查找用户数据，判断用户是否存在，不存在抛出异常
        $user =UserModel::get($uid);//  使用模型的get方法获取用户信息
        if(!$user){ //  对用户信息判断如果不存在抛出异常
            throw new UserException();
        }
        //  4.获取用户从客户端提交来的地址信息
        //$dateArray = getDatas();//  写一段伪代码代表提取从客户端提交的地址信息
        $dateArray = $validate->getDataByRule(input('post.'));//    调用按照过滤规则提取数据的方法
        //  5.根据用户信息是否存在来判断是添加地址还是更新地址
        $userAddress = $user->address;
        if(!$userAddress){
            //  使用当前模型的关联模型方法的save方法实现信息存储
            $user->address()->save($dateArray);
        }else{
            //  使用当前模型的关联模型方法的Update方法实现信息更新
            //  注意使用模型的关联模型的方法进行信息更新使用方法！！！
            $user->address->save($dateArray);
        }
        //  向客户端返回处理结果
        //return $user;// 标准的RestFul Api会将进行操作的资源返回给客户端
        //但是这里的客户端不需要我们将操作执行的结果返回，只需要知道结果是否完成。
        //return new SuccessMessage();//  调用结果处理方法将操作执行成功返回给客户端
        return json(new SuccessMessage(),201);//    自定义返回状态码需要用json对结果进行序列化
    }
}