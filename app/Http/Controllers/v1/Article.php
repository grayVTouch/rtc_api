<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:15
 */

namespace App\Http\Controllers\v1;


use App\Customize\v1\action\ArticleAction;

class Article extends Auth
{
    public function information()
    {
        $param = $this->request->post();
        $param['article_type_id'] = 7;
        $res = ArticleAction::list($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }

    public function detail()
    {
        $param = $this->request->post();
        $param['article_id'] = $param['article_id'] ?? '';
        $res = ArticleAction::detail($this , $param);
        if ($res['code'] != 0) {
            return error($res['data'] , $res['code']);
        }
        return success($res['data']);
    }
}