<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 13:48
 */

namespace App\Customize\v1\action;



use App\Customize\v1\model\FriendCircleCommendationModel;
use App\Customize\v1\model\FriendCircleCommentModel;
use App\Customize\v1\model\FriendCircleMediaModel;
use App\Customize\v1\model\FriendCircleModel;
use App\Customize\v1\model\FriendCircleUnreadModel;
use App\Customize\v1\model\FriendModel;
use App\Customize\v1\util\ChatUtil;
use App\Customize\v1\util\FriendCircleUtil;
use App\Http\Controllers\v1\Auth;
use App\Http\Controllers\v1\FriendCircle;
use function core\array_unit;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class FriendCircleAction extends Action
{
    public static function publish(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'type' => 'required' ,
            'content' => 'required' ,
            'media' => 'required' ,
        ]);
        if (!$validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $media = json_decode($param['media'] , true);
        if (empty($media)) {
            return self::error("请提供朋友圈媒体文件");
        }
        $type_for_friend_circle = api_config('business.type_for_friend_circle');
        if (!in_array($param['type'] , $type_for_friend_circle)) {
            return self::error("不支持的 type，当前受支持的 type 有：" . implode(',' , $type_for_friend_circle));
        }
        try {
            DB::beginTransaction();
            $friend_circle_id = FriendCircleModel::insertGetId(array_unit($param , [
                'type' ,
                'content' ,
            ]));
            // 新增媒体
            foreach ($media as $v)
            {
                FriendCircleMediaModel::insertGetId([
                    'friend_circle_id' => $friend_circle_id ,
                    'friend_circle_sender' => user()->id ,
                    'mime'  => $v['mime'] ,
                    'src'   => $v['src'] ,
                ]);
            }
            /**
             * todo 先按照如下的方式做，后期后果有时间再继续完善
             * 新增相关人员的朋友圈未读消息数量
             *
             * 根据设置的朋友圈权限，如果 公开
             * 1. 好友关系
             * 2. 排除好友里面不查看自己朋友圈的那些人
             */
            $friends = FriendModel::myFriendExcludeBlockUserByUserId(user()->id);
            $user_ids = [];
            foreach ($friends as $v)
            {
                $friend_circle_unread = FriendCircleUnreadModel::findOrCreateByUserId($v->friend_id);
                FriendCircleUnreadModel::updateById($friend_circle_unread->id , [
                    'total_unread_count' => $friend_circle_unread->total_unread_count++ ,
                    'friend_circle_unread_count' => $friend_circle_unread->friend_circle_unread_count++ ,
                ]);
                $user_ids[] = $v->friend_id;
            }
            DB::commit();
            ChatUtil::api_notifyAll($user_ids , 'refresh_friend_circle_unread');
            return self::success();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    // 朋友圈列表
    public static function myFriendCircle(Auth $auth , array $param)
    {
        $limit_id = empty($param['limit_id']) ? 0 : $param['limit_id'];
        $limit = empty($param['limit']) ? api_config('app.limit') : $param['limit'];
        $user_ids = FriendModel::getFriendIdsByUserId(user()->id);
        $user_ids[] = user()->id;
        $res = FriendCircleModel::myFriendCircle(user()->id , $user_ids , $limit_id , $limit);
        foreach ($res as $v)
        {
            // 获取评论
            $comment = FriendCircleCommentModel::getByFriendCircleIdAndUserIds($v->id , $user_ids);
            // 获取点赞
            $commendation = FriendCircleCommendationModel::getByFriendCircleIdAndUserIds($v->id , $user_ids);
            // 获取媒体
            $media = FriendCircleMediaModel::getByFriendCircleId($v->id);
            // 针对评论的处理
            foreach ($comment as $v1)
            {
                FriendCircleUtil::handleComment($v1);
            }
            foreach ($commendation as $v1)
            {
                FriendCircleUtil::handleCommendation($v);
            }
            $v->comment = $comment;
            $v->commendation = $commendation;
            $v->media = $media;
            FriendCircleUtil::handleFriendCircle($v);
        }
        return self::success($res);
    }


    // 单条朋友圈
    public static function friendCircle(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'friend_circle_id' => 'required'
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $friend_circle = FriendCircleModel::findById($param['friend_circle_id']);
        if (empty($friend_circle)) {
            return self::error("未找到朋友圈相关信息" , 404);
        }
        $user_ids = FriendModel::getFriendIdsByUserId(user()->id);
        $user_ids[] = user()->id;
        // 获取评论
        $comment = FriendCircleCommentModel::getByFriendCircleIdAndUserIds($friend_circle->id , $user_ids);
        // 获取点赞
        $commendation = FriendCircleCommendationModel::getByFriendCircleIdAndUserIds($friend_circle->id , $user_ids);
        // 获取媒体
        $media = FriendCircleMediaModel::getByFriendCircleId($friend_circle->id);
        // 针对评论的处理
        foreach ($comment as $v)
        {
            FriendCircleUtil::handleComment($v);
        }
        foreach ($commendation as $v)
        {
            FriendCircleUtil::handleCommendation($v);
        }
        $friend_circle->comment = $comment;
        $friend_circle->commendation = $commendation;
        $friend_circle->media = $media;
        FriendCircleUtil::handleFriendCircle($friend_circle);
        return self::success($friend_circle);
    }

    // 删除朋友圈
    public static function delFriendCircle(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'friend_circle_id' => 'required'
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $friend_circle = FriendCircleModel::findById($param['friend_circle_id']);
        if (empty($friend_circle)) {
            return self::error("未找到朋友圈相关信息" , 404);
        }
        try {
            DB::beginTransaction();
            FriendCircleCommendationModel::delByFriendCircleId($friend_circle->id);
            FriendCircleCommentModel::delByFriendCircleId($friend_circle->id);
            FriendCircleMediaModel::delByFriendCircleId($friend_circle->id);
            FriendCircleModel::delById($friend_circle->id);
            DB::commit();
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }


    // 朋友圈点赞（取消点赞）
    public static function commendation(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'friend_circle_id'
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $friend_circle = FriendCircleModel::findById($param['friend_circle_id']);
        if (empty($friend_circle)) {
            return self::error('未找到对应的朋友圈信息' , 404);
        }
        try {
            DB::beginTransaction();
            $res = FriendCircleCommendationModel::findByUserIdAndFriendCircleId($param['friend_circle_id'] , user()->id);
            $type = '';
            $user_ids = [];
            if (empty($res)) {
                $type = 'add';
                // 新增点赞
                FriendCircleCommendationModel::insertGetId([
                    'user_id'   => user()->id ,
                    'friend_circle_sender'  => $friend_circle->user_id ,
                    'friend_circle_id' => $friend_circle->id
                ]);
                $friends = FriendModel::myFriendByUserId(user()->id);
                foreach ($friends as $v)
                {
                    $friend_circle_unread = FriendCircleUnreadModel::findOrCreateByUserId($v->friend_id);
                    FriendCircleUnreadModel::updateById($friend_circle_unread->id , [
                        'commendation_unread_count' => $friend_circle_unread->commendation_unread_count++ ,
                        'common_unread_count' => $friend_circle_unread->common_unread_count++
                    ]);
                    $user_ids[] = $v->friend_id;
                }
            } else {
                $type = 'cancel';
                // 取消点赞
                FriendCircleCommendationModel::delByUserIdAndFriendCircleId(user()->id , $friend_circle->id);
            }
            DB::commit();
            if ($type == 'add') {
                ChatUtil::api_notifyAll($user_ids , 'refresh_friend_circle_unread');
            }
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public static function comment(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'friend_circle_id' ,
            'content' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $friend_circle = FriendCircleModel::findById($param['friend_circle_id']);
        if (empty($friend_circle)) {
            return self::error('未找到对应的朋友圈信息' , 404);
        }
        if (!empty($param['p_id'])) {
            // 检查上级评论是否存在
            $comment = FriendCircleCommentModel::findById($param['p_id']);
            if (empty($comment)) {
                return self::error('上级评论未找到' , 404);
            }
        } else {
            $param['p_id'] = empty($param['p_id']) ? 0 : $param['p_id'];
        }
        try {
            DB::beginTransaction();
            FriendCircleCommentModel::insertGetId([
                'friend_circle_id' => $friend_circle->id ,
                'friend_circle_sender' => $friend_circle->user_id ,
                'user_id' => user()->id ,
                'p_id' => $param['p_id'] ,
                'content' => $param['content'] ,
            ]);
            $friends = FriendModel::myFriendByUserId(user()->id);
            $user_ids = [];
            foreach ($friends as $v)
            {
                $friend_circle_unread = FriendCircleUnreadModel::findOrCreateByUserId($v->friend_id);
                FriendCircleUnreadModel::updateById($friend_circle_unread->id , [
                    'comment_unread_count' => $friend_circle_unread->comment_unread_count++ ,
                    'common_unread_count' => $friend_circle_unread->common_unread_count++
                ]);
                $user_ids[] = $v->friend_id;
            }
            DB::commit();
            ChatUtil::api_notifyAll($user_ids , 'refresh_friend_circle_unread');
            return self::success();
        } catch(Exception $e) {
            DB::rollBack();
            throw $e;
        }

    }

    // 删除评论
    public static function delComment(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'friend_circle_comment_id' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $comment = FriendCircleCommentModel::findById($param['friend_circle_comment_id']);
        if (empty($comment)) {
            return self::error('未找到评论信息' , 404);
        }
        FriendCircleCommentModel::delById($param['friend_circle_comment_id']);
        return self::success();
    }



}