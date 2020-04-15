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
}