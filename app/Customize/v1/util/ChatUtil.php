<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/16
 * Time: 10:05
 */

namespace App\Customize\v1\util;


use Core\Lib\Http;

class ChatUtil extends Util
{

    // 通知
    public static $url = 'http://127.0.0.1:10001';

    public static function genUrl($uri)
    {
        $uri = ltrim($uri , '/');
        return self::$url . '/' . $uri;
    }

    public static function api_notifyAll($user_id , $type , $data = '')
    {
        $res = Http::post(self::genUrl('/WebV1/PushNoAuth/notifyAll') , [
            'user_id'   => $user_id ,
            'type'      => $type ,
            'data'      => $data ,
        ]);
        if (empty($res)) {
            return self::error('网络错误 或 远程接口没有返回任何信息');
        }
        $res = json_decode($res , true);
        if (empty($res)) {
            return self::error('远程接口没有返回任何数据');
        }
        if ($res['code'] != 0) {
            return self::error('远程接口错误：' . $res['data'] , $res['code']);
        }
        return self::success($res['data']);
    }

    public static function api_notify($user_id , $type , $data = '')
    {
        $res = Http::post(self::genUrl('/WebV1/PushNoAuth/notifyAll') , [
            'user_id'   => $user_id ,
            'type'      => $type ,
            'data'      => $data ,
        ]);
        if (empty($res)) {
            return self::error('网络错误 或 远程接口没有返回任何信息');
        }
        $res = json_decode($res , true);
        if (empty($res)) {
            return self::error('远程接口没有返回任何数据');
        }
        if ($res['code'] != 0) {
            return self::error('远程接口错误：' . $res['data'] , $res['code']);
        }
        return self::success($res['data']);
    }
}
