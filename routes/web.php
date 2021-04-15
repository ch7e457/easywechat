<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function (\Illuminate\Http\Request $request) {
    return "|name:".$request->session()->get('wcName').'
    |id:'.$request->session()->get('wcOpenId')."
    |av:".$request->session()->get('wcAvatar');
//    return view('welcome');
})->middleware('wcOauth');

Route::get('/test',function(\Illuminate\Http\Request $request){
   return 'hello world';
});

/*
 * 开发后台验证
 */
Route::any('/wechat/verify', 'WeChatController@verify');
/*
 * 网页授权
 */
//Route::any('wechat/redirect','WeChatController@redirect');
/*
 * 授权回调
 */
Route::any('wechat/callback','WeChatController@callback');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

