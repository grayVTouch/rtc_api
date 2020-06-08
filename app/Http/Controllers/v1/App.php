<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:00
 */

namespace App\Http\Controllers\v1;


use App\Customize\v1\action\AppAction;

class App extends Auth
{
    // 应用列表
    public function app()
    {
        $param = $this->request->post();
        $res = AppAction::app($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function detail()
    {
        $param = $this->request->post();
        $param['app_id'] = $param['app_id'] ?? '';
        $res = AppAction::detail($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}