<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 14:35
 */

namespace App\Customize\v1\model;


class FriendModel extends Model
{
    protected $table = 'rtc_friend';

    public static function myFriendExcludeBlockUserByUserId($user_id)
    {

//        $sql = <<<EOT
//            select * from rtc_friend as f where f.user_id = 1 and
//              -- 第一：排除以设置屏蔽的用户
//              not exists (select id from rtc_friend_circle_visible where user_id = f.user_id and relative_user_id = f.friend_id and type = 1)
//              and
//              -- 第二：排除已屏蔽我的用户
//              not exists (select id from rtc_friend_circle_visible where user_id = f.friend_id and relative_user_id = f.user_id and type = 1)
//EOT;

        $res = self::where('user_id' , $user_id)
            ->whereNotExists(function($query){
                $query->select('id')
                    ->from('rtc_friend_circle_visible')
                    ->whereRaw('
                        user_id = rtc_friend.user_id and 
                        relation_user_id = rtc_friend.friend_id and
                        type = 1 
                    ');
            })
            ->whereNotExists(function($query){
                $query->select('id')
                    ->from('rtc_friend_circle_visible')
                    ->whereRaw('
                        user_id = rtc_friend.friend_id and 
                        relation_user_id = rtc_friend.user_id and
                        type = 1 
                    ');
            })
            ->get();
        self::multiple($res);
        return $res;
    }

    public static function myFriendByUserId($user_id)
    {
        $res = self::where('user_id' , $user_id)
            ->get();
        self::multiple($res);
        return $res;
    }

    public static function getByUserId($user_id)
    {
        $res = self::where('user_id' , $user_id)
            ->get();
        self::multiple($res);
        return $res;
    }

    public static function getFriendIdsByUserId($user_id)
    {
        $res = self::getByUserId($user_id);
        $user_ids = [];
        foreach ($res as $v)
        {
            $user_ids[] = $v->friend_id;
        }
        return $user_ids;
    }

    public static function findByUserIdAndFriendId($user_id , $friend_id)
    {
        $res = self::where([
                ['user_id' , '=' , $user_id] ,
                ['friend_id' , '=' , $friend_id] ,
            ])
            ->first();
        self::single($res);
        return $res;
    }
}