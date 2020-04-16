<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/16
 * Time: 13:53
 */

namespace App\Customize\v1\util;


use App\Customize\v1\model\FriendCircleCommendationModel;
use App\Customize\v1\model\UserModel;

class FriendCircleUtil extends Util
{
    public static function handleComment($comment , $relation_user_id = 0)
    {
        $comment->user = UserModel::findById($comment->user_id);
        if (empty($relation_user_id)) {
            // 评论处理
            UserUtil::handle($comment->user , $relation_user_id);
        }
    }

    public static function handleCommendation($commendation , $relation_user_id = 0)
    {
        $commendation->user = UserModel::findById($commendation->user_id);
        if (empty($relation_user_id)) {
            // 评论处理
            UserUtil::handle($commendation->user , $relation_user_id);
        }
    }

    public static function handleFriendCircle($friend_circle , $relation_user_id = 0)
    {
        $friend_circle->is_commendation = empty(FriendCircleCommendationModel::findByUserIdAndFriendCircleId($friend_circle->id , $relation_user_id)) ? 0 : 1;
    }
}