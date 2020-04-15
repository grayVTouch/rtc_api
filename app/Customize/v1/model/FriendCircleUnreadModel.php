<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 14:18
 */

namespace App\Customize\v1\model;


class FriendCircleUnreadModel extends Model
{
    protected $table = 'rtc_friend_circle_unread';

    public static function updateByUserId($user_id , array $data = [])
    {
        return self::where('user_id' , $user_id)
            ->update($data);
    }

    public static function findOrCreateByUserId($user_id)
    {
        $res = self::where('user_id' , $user_id)
            ->find();
        if (empty($res)) {
            $id = self::insertGetId([
                'user_id' => $user_id
            ]);
            $res = self::find($id);
        }
        self::single($res);
        return $res;
    }
}