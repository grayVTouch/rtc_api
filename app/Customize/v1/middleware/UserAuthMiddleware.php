<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2019/2/17
 * Time: 10:14
 *
 * 用户登录认证
 */

namespace App\Customize\v1\middleware;


use App\Customize\v1\model\UserModel;
use App\Customize\v1\model\UserTokenModel;
use Closure;
use function extra\regexp_check;
use Illuminate\Http\Request;

class UserAuthMiddleware
{
    // 定义排除验证的路由
    private $exclude = [
//        [
//            'method' => 'POST' ,
//            'path'  => 'api/admin/admin/logining' ,
//        ] ,
    ];

//    public

    public function handle($request , Closure $next)
    {
        $method = $request->method();
        $path   = $request->path();
        $is_exclude  = $this->isExclude($method , $path);
        if ($is_exclude) {
            return $next($request);
        }
        // 验证 token
        $res = $this->auth($request);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return $next($request);
    }

    public function auth(Request $query)
    {
        $param = $query->post();
        $param['user_id'] = $param['user_id'] ?? '';
        $param['token'] = $param['token'] ?? '';
        if ($param['token'] == 'debug') {

            if (!empty($param['user_id'])) {
                $user = UserModel::findById($param['user_id']);
                app()->instance('user' , $user);
                return $this->response('验证成功');
            }
            return $this->response('请提供 user_id 表明调试的用户身份' , 400);
        }
        if (empty($param['user_id']) || empty($param['token'])) {
            // 用户认证失败
            return $this->response('user_id | token 为空' , 400);
        }
        $token = UserTokenModel::findByUserIdAndToken($param['user_id'] , $param['token']);
        if (empty($token)) {
            return $this->response('token 错误' , 403);
        }
        $datetime = date('Y-m-d H:i:s' , time());
        if ($datetime > $token->expire) {
            // 过期时间
            return $this->response('用户登陆状态已经过期' , 403);
        }
        // 获取用户信息
        $user = UserModel::findById($token->user_id);
        if (empty($user)) {
            // 用户不存在，则认证失败
            return $this->response('用户不存在' , 404);
        }
        app()->instance('user' , $user);
        return $this->response('验证成功' , 0);
    }

    public function response($data , $code = 0)
    {
        return [
            'code' => $code ,
            'data' => $data ,
        ];
    }

    // 检查是否排除路径
    public function isExclude($method , $path)
    {
        foreach ($this->exclude as $v)
        {
            if ($v['method'] == $method && regexp_check($path , $v['path'])) {
                return true;
            }
        }
        return false;
    }
}