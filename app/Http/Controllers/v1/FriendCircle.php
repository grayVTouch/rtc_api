<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 13:42
 */

namespace App\Http\Controllers\v1;



use App\Customize\v1\action\FriendCircleAction;

class FriendCircle extends Auth
{
    // 发布朋友圈
    public function publish()
    {
        $param = $this->request->post();
        $param['type'] = $param['type'] ?? '';
        $param['content'] = $param['content'] ?? '';
        $param['media'] = $param['media'] ?? '';
        $res = FriendCircleAction::publish($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function myFriendCircle()
    {
        $param = $this->request->post();
        $param['limit_id'] = $param['limit_id'] ?? '';
        $param['limit'] = $param['limit'] ?? '';
        $res = FriendCircleAction::myFriendCircle($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function friendCircle()
    {
        $param = $this->request->post();
        $param['friend_circle_id'] = $param['friend_circle_id'] ?? '';
        $res = FriendCircleAction::myFriendCircle($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function delFriendCircle()
    {
        $param = $this->request->post();
        $param['friend_circle_id'] = $param['friend_circle_id'] ?? '';
        $res = FriendCircleAction::delFriendCircle($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function commendation()
    {
        $param = $this->request->post();
        $param['friend_circle_id'] = $param['friend_circle_id'] ?? '';
        $res = FriendCircleAction::commendation($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function comment()
    {
        $param = $this->request->post();
        $param['friend_circle_id'] = $param['friend_circle_id'] ?? '';
        $param['content'] = $param['content'] ?? '';
        $res = FriendCircleAction::comment($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function delComment()
    {
        $param = $this->request->post();
        $param['friend_circle_comment_id'] = $param['friend_circle_id'] ?? '';
        $res = FriendCircleAction::delComment($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

}