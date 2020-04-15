<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2019/3/24
 * Time: 14:27
 */

namespace App\Customize\admin\middleware;

use Closure;

use Core\Lib\Throwable as ThrowableLib;

class ThrowableMiddleware
{
    private $header = [
        'Access-Control-Allow-Origin' => '*' ,
        'Access-Control-Allow-Methods'      => 'GET,POST,PUT,PATCH,DELETE' ,
        'Access-Control-Allow-Credentials'  => 'false' ,
        'Access-Control-Allow-Headers'      => 'Authorization,Content-Type,X-Request-With,Ajax-Request' ,
    ];

    public function handle($request , Closure $next)
    {
        $this->registerHandler();
        return $next($request);
    }

    private function registerHandler()
    {
        $throwable = new ThrowableLib($this->header);
        // 注册异常处理
        set_exception_handler([$throwable , 'exceptionJsonHandlerInDev']);
        // 注册错误处理
        set_error_handler([$throwable , 'errorJsonHandlerInDev']);
        // 注册致命错误
        register_shutdown_function([$throwable , 'fetalErrorJsonHandlerInDev']);
    }
}