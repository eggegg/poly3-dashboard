<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    /**
     * use username or email as the login name
     *
     * @return string
     */
    public function username()
    {
        return 'username';
    }


    /**
     * /login 跳转poly登录
     *
     * @return mixed
     */
    public function showLoginForm()
    {
        $polyLoginUrl = env('APP_POLY_LOGIN_URL', false);
        if(!$polyLoginUrl) {
            echo 'wrong login url';
        }

        $redirectLoginUrl = $polyLoginUrl.'&callback='.url('/login_check').'/';
        header("location: $redirectLoginUrl");
        exit;
    }

    /**
     * /login_check/{token} 跳转之后处理登录逻辑
     *
     */
    public function login($token)
    {
        $params = explode('&', $token);

        $authToken=null;
        foreach ($params as $param) {
            if(starts_with($param, 'auth=')) {
                $authToken = substr($param,5);
            }
        }
        if(!$authToken){
            echo 'wrong auth token';
            exit;
        }

        $authUsername = null;

        // Check token procedure , copy from
        $auth_arr = array();
        $time_arr = array();
        $auth_arr[0] = substr($authToken,0,12);
        $auth_arr[1] = substr($authToken,12,12);
        $auth_arr[2] = substr($authToken,24,12);
        $auth_arr[3] = substr($authToken,36,12);
        $time_arr[0] = substr($auth_arr[0],8,4);
        $time_arr[1] = substr($auth_arr[1],9,3);
        $time_arr[2] = substr($auth_arr[2],9,3);
        $curr_time = time();
        $time = intval(implode('', $time_arr));
        if(abs($time-$curr_time)>300){//判断时间
            die('授权过期，请重新登录！');
        }

        $str = $auth_arr[0].$auth_arr[1].$time.$auth_arr[2].$auth_arr[0].$time.$auth_arr[1].$auth_arr[2].$time.$auth_arr[0].$auth_arr[1].$auth_arr[2];
        $auth = substr(md5($str),($time_arr[0]%16),12);
        if($auth==$auth_arr[3]){

            $url = env('APP_POLY_CHECK_URL', false);
            $url .= $authToken;

            $checktoken = file_get_contents($url);
            $checktoken = json_decode($checktoken, true);
            if($checktoken['code']!==0){
                die($checktoken['msg']);
            }
            $user = unserialize($checktoken['user']);
            if($user['username'])  {
                $authUsername = $user['username'];
            }

        }else{
            die('token验证失败！');
        }


        // 获取到了合法的username
        if(!$authUsername) {
            die('login failure');
        }


        $myUser = User::where('name', $authUsername)->first();


        //没找到用户，初始化
        if(!$myUser) {

            $myUser = User::create([
                'name' => $authUsername,
                'password' => bcrypt('secret'),
                'email' => 'example'.time().'@test.com',
            ]);

            //TODO 需要查出用户email等信息

            //设置默认权限
            $myUser->assignRole('Admin');

        }

        //登录并跳转
        $userData = array(
            'name'     => $myUser->name,
            'password'  => 'secret'
        );

        if (Auth::attempt($userData)) {
            // validation successful!
            return Redirect::to('/home');

        } else {

            // validation not successful, send back to form
            return Redirect::to('login');
        }


    }

}
