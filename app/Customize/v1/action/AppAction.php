<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:01
 */

namespace App\Customize\v1\action;


use App\Customize\v1\model\AppModel;
use App\Http\Controllers\v1\Auth;
use Illuminate\Support\Facades\Validator;

class AppAction extends Action
{
    public static function app(Auth $auth , array $param)
    {
        $limit = empty($param['limit']) ? api_config('app.limit') : $param['limit'];
//        $res = AppModel::app([] , $limit);
        $res = AppModel::app($limit);
        return self::success($res);
    }

    public static function detail(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'app_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $res = AppModel::findById($param['app_id']);
        if (empty($res)) {
            return self::error('未找到应用信息' , 404);
        }
        return self::success($res);
    }
}