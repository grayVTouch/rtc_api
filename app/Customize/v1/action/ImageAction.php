<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:11
 */

namespace App\Customize\v1\action;


use App\Customize\v1\model\ImageModel;
use App\Http\Controllers\v1\Auth;

class ImageAction extends Action
{
    public static function image(Auth $auth , array $param)
    {
        $position = 1;
        $res = ImageModel::image($position);
        return self::success($res);
    }
}