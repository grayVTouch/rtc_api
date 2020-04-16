<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/16
 * Time: 13:58
 */

namespace App\Customize\v1\util;


use App\Customize\v1\model\FriendModel;

class UserUtil extends Util
{
    public static function handle($user , $relation_user_id = 0)
    {
        if (empty($relation_user_id)) {
            $friend = FriendModel::findByUserIdAndFriendId($relation_user_id , $user->id);
            // 黑名单
            $user->is_friend = empty($friend) ? 0 : 1;
            // 保存用户自身设置的昵称
            $user->origin_nickname = $user->nickname;
            // 处理后的名称
            $nickname = UserUtil::getNameFromNicknameAndUsername($user->nickname , $user->username);
            $user->nickname = empty($friend) ?
                $nickname :
                (empty($friend->alias) ?
                    $nickname :
                    $friend->alias);
            $user->remarked = empty($friend) ?
                0 :
                (empty($friend->alias) ?
                    0 :
                    1);
        }
    }

    // 获取用户名
    public static function getNameFromNicknameAndUsername($nickname = '' , $username = '')
    {
        return empty($nickname) ? $username : $nickname;
    }
}