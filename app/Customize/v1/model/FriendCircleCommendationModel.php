<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 11:31
 */

namespace App\Customize\v1\model;


class FriendCircleCommendationModel extends Model
{
    protected $table = 'rtc_friend_circle_commendation';

    public function findByUserIdAndFriendCircleId($user_id , $friend_circle_id)
    {
        $res = self::where([
                ['user_id' , '=' , $user_id] ,
                ['friend_circle_id' , '=' , $friend_circle_id] ,
            ])
            ->first();
        self::single($res);
        return $res;
    }

    public static function delByUserIdAndFriendCircleId($user_id , $friend_circle_id)
    {
        return self::where([
            ['user_id' , '=' , $user_id] ,
            ['friend_circle_id' , '=' , $friend_circle_id] ,
        ])->delete();
    }
}