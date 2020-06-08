<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:11
 */

namespace App\Customize\v1\action;


use App\Customize\v1\model\ArticleModel;
use App\Customize\v1\model\ImageModel;
use App\Http\Controllers\v1\Auth;
use Core\Lib\Validator;

class ArticleAction extends Action
{
    public static function list(Auth $auth , array $param)
    {
        $limit = empty($param['limit']) ? api_config('app.limit') : $param['limit'];
        $filter = [];
        $filter['article_type_id'] = $param['article_type_id'] ?? '';
        $res = ArticleModel::list($filter , $limit);
        return self::success($res);
    }

    public static function detail(Auth $auth , array $param)
    {
        $validator = Validator::make($param , [
            'article_id' => 'required' ,
        ]);
        if ($validator->fails()) {
            return self::error(get_form_error($validator));
        }
        $res = ArticleModel::findById($param['article_id']);
        if (empty($res)) {
            return self::error('' , 404);
        }
        return self::success($res);
    }
}