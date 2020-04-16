<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/16
 * Time: 10:04
 */

namespace App\Customize\v1\util;


class Util
{
    public static function success($data , $code = 0)
    {
        return self::response($data , $code);
    }

    public static function error($data , $code = 400)
    {
        return self::response($data , $code);
    }

    public static function response($data , $code)
    {
        return [
            'code' => $code ,
            'data' => $data ,
        ];
    }
}