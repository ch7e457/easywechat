<?php
/**
 * Created by PhpStorm.
 * User: shenfeng
 * Date: 16/5/18
 * Time: PM5:31
 */
namespace App\Http\Controllers;

use EasyWeChat\Factory;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class WechatController extends Controller{

    private $config = array();

    function __construct()
    {
        $this->config = [
            'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID'),         // AppID
            'secret' => env('WECHAT_OFFICIAL_ACCOUNT_SECRET'),    // AppSecret
            'token' => env('WECHAT_OFFICIAL_ACCOUNT_TOKEN'),           // Token
            'aes_key' => env('WECHAT_OFFICIAL_ACCOUNT_AES_KEY'),                 // EncodingAESKey
            'response_type' => 'array',
            'log' => [
                'level' => 'debug',
                'file' => __DIR__.'/wechat.log',
            ],
        ];
    }


    public function verify(Request $request)
    {
        $app = Factory::officialAccount($this->config);
        $app->server->push(function ($message) {
            return "您好！欢迎使用 EasyWeChat!";
        });
        return $app->server->serve();
    }

//    public function redirect(){
//        $app = Factory::officialAccount($this->config);
//        $response = $app->oauth->scopes(['snsapi_userinfo'])
//            ->redirect('http://127.0.0.1:8000/wechat/callback');
//        return $response;
//    }

    public function callback(Request $request){
        $app = Factory::officialAccount($this->config);
        $user = $app->oauth->user();
        $request->session()->put('wcOpenId',$user->getId());
        $request->session()->put('wcName',$user->getName());
        $request->session()->put('wcAvatar',$user->getAvatar());
        return redirect($request->session()->get('originalUrl'));
    }

}