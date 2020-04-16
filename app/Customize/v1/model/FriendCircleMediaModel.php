<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 14:14
 */

namespace App\Customize\v1\model;


use function core\convert_obj;

class FriendCircleMediaModel extends Model
{
    protected $table = 'rtc_friend_circle_media';

    public static function getByFriendCircleId($friend_circle_id)
    {
        $res = self::where('friend_circle_id' , $friend_circle_id)
            ->get();
        $res = convert_obj($res);
        return $res;
    }

    public static function delByFriendCircleId($friend_circle_id)
    {
        return self::where('friend_circle_id' , $friend_circle_id)
            ->delete();
    }
}