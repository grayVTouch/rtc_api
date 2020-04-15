<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 14:04
 */

namespace App\Customize\v1\model;


class FriendCircleModel extends Model
{
    protected $table = 'rtc_friend_circle';

    public static function myFriendCircle($user_id)
    {
        $res = self::where('user_id' , $user_id)
            ->whereNotExists(function($query){
                $query->select('id')
                    ->from('rtc_friend_circle_visible')
                    ->whereRaw('
                        user_id = rtc_friend.user_id and 
                        relative_user-id = rtc_friend.friend_id and
                        type = 1 
                    ');
            })
            ->whereNotExists(function($query){
                $query->select('id')
                    ->from('rtc_friend_circle_visible')
                    ->whereRaw('
                        user_id = rtc_friend.friend_id and 
                        relative_user-id = rtc_friend.user_id and
                        type = 1 
                    ');
            })
            ->get();
    }
}