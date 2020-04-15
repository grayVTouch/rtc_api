<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 13:43
 */

namespace App\Http\Controllers\v1;


use App\Customize\v1\middleware\UserAuthMiddleware;
use Illuminate\Http\Request;

class Auth extends Base
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
        $this->middleware(UserAuthMiddleware::class);
    }
}