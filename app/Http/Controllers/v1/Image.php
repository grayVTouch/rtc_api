<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:15
 */

namespace App\Http\Controllers\v1;


use App\Customize\v1\action\ImageAction;

class Image extends Auth
{
    public function image()
    {
        $param = $this->request->post();
        $res = ImageAction::image($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}