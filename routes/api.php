<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


/**
 * 朋友圈
 */
Route::prefix("v1")
    ->namespace("v1")
    ->group(function(){
        // 数据测试
        Route::get("index/index" , "Index@index");
        // 发布朋友圈

        Route::post("friend_circle/publish" , "FriendCircle@publish");
        Route::post("friend_circle/comment" , "FriendCircle@comment");
        Route::post("friend_circle/commendate" , "FriendCircle@commendate");
        Route::post("friend_circle/delComment" , "FriendCircle@delComment");
        Route::post("friend_circle/myFriendCircle" , "FriendCircle@myFriendCircle");
        Route::post("friend_circle/comments" , "FriendCircle@comments");
        Route::post("friend_circle/commendation" , "FriendCircle@commendation");
    });