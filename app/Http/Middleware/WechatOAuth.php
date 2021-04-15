<?php

namespace App\Http\Middleware;

use Closure;
use EasyWeChat\Factory;

class WechatOAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$request->session()->has("wcOpenId")){
            $request->session()->put('originalUrl',$request->getUri());
            $config = [
                'app_id' => env('WECHAT_OFFICIAL_ACCOUNT_APPID'),         // AppID
                'secret' => env('WECHAT_OFFICIAL_ACCOUNT_SECRET'),    // AppSecret
                'token' => env('WECHAT_OFFICIAL_ACCOUNT_TOKEN'),           // Token
                'aes_key' => env('WECHAT_OFFICIAL_ACCOUNT_AES_KEY'),                 // EncodingAESKey
                'response_type' => 'array',
                'log' => [
                    'level' => 'debug',
                    'file' => __DIR__.'/wechat.log',
                ],
                'oauth' => ['snsapi_userinfo']
            ];

            $app = Factory::officialAccount($config);
            $response = $app->oauth->scopes(['snsapi_userinfo'])
                ->redirect('http://wx.qwerty.wang/wechat/callback');
            return $response;
        }
        return $next($request);
    }
}
