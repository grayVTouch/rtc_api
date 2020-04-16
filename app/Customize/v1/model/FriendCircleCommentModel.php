<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 11:31
 */

namespace App\Customize\v1\model;


use function core\convert_obj;

class FriendCircleCommentModel extends Model
{
    protected $table = 'rtc_friend_circle_comment';

    // 获取朋友圈底下的评论（仅限好友 和 自己的评论）
    public static function getByFriendCircleIdAndUserIds($friend_circle_id , array $user_ids = [])
    {
        $res = self::where('friend_circle_id' , $friend_circle_id)
            ->whereIn('user_id' , $user_ids)
            ->orderBy('id' , 'asc')
            ->get();
        $res = convert_obj($res);
        self::multiple($res);
        return $res;
    }

    public static function delByFriendCircleId($friend_circle_id)
    {
        return self::where('friend_circle_id' , $friend_circle_id)
            ->delete();
    }
}