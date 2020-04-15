<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 11:31
 */

namespace App\Customize\v1\model;


class UserTokenModel extends Model
{
    protected $table = 'rtc_user_token';

    public static function findByUserIdAndToken($user_id , $token)
    {
        $res = self::where([
            ['user_id' , '=' , $user_id] ,
            ['token' , '=' , $token] ,
        ])->first();
        self::single($res);
        return $res;
    }
}