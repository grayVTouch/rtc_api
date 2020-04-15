<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 10:03
 */

namespace App\Http\Controllers\v1;

use App\Customize\Admin\Http\Middleware\LoaderMiddleware;
use App\Customize\admin\middleware\ThrowableMiddleware;
use App\Customize\v1\middleware\CustomizeMiddleware;
use App\Customize\v1\middleware\UserAuthMiddleware;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
use ReflectionClass;

class Base extends Controller
{
    public function __construct(Request $request)
    {
        $this->request = $request;
        // 检查父类是否有构造函数
        // 如果存在则调用
        $parent = new ReflectionClass(parent::class);
        if ($parent->hasMethod('__construct')) {
            parent::__construct();
        }
        // 在这边做一些模块内共享的事情
        $this->middleware(CustomizeMiddleware::class);
        $this->middleware(ThrowableMiddleware::class);
        $this->middleware(LoaderMiddleware::class);
    }
}