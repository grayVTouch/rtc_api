<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 11:27
 */

use function extra\config;

// 获取配置配置文件
function api_config($k , $args = [])
{
    $dir = __DIR__ . '/../config';
    return config($dir , $k , $args);
}

// 获取对应值
function get_value($k , $val = '')
{
    $range = config($k);
    foreach ($range as $k => $v)
    {
        if ($k == $val) {
            return $v;
        }
    }
    return '';
}

// 成功
function success($data = '' , $code = 200)
{
    return api_response($data , $code);
}

// 失败
function error($data = '' , $code = 400)
{
    return api_response($data , $code);
}

// 响应
function api_response($data , $code)
{
    $language = request()->post('language');
    $language = $language ?? 'cn';
    return response()
        ->json(compact('code' , 'data'));
}

// 当前登录用户
function user()
{
    try {
        return app()->make('user');
    } catch(ReflectionException $e) {
        return null;
    }
}

// 格式化 validator 表单验证的错误信息
function form_error($validator)
{
    $error = $validator->errors()->messages();
    $res = [];
    foreach ($error as $k => $v)
    {
        $res[$k] = count($v) > 0 ? $v[0] : '';
    }
    return error($res , 400);
}

// 获取首个错误
function get_form_error($validator)
{
    $error = $validator->errors()->messages();
    $error = array_values($error);
    if (count($error) == 0) {
        return '';
    }
    $error = $error[0];
    if (count($error) == 0) {
        return '';
    }
    return $error[0];
}
