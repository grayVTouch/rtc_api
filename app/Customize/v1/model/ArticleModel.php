<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:03
 */

namespace App\Customize\v1\model;


use function core\convert_obj;

class ArticleModel extends Model
{
    protected $table = 'rtc_article';

    public function articleType()
    {
        return $this->belongsTo(ArticleTypeModel::class , 'article_type_id' , 'id');
    }

    public static function list($filter = [] , $limit = 20)
    {
        $where = [];
        if (!empty($filter['article_type_id'])) {
            $where[] = ['article_type_id' , '=' , $filter['article_type_id']];
        }
        $res = self::with(['articleType'])
            ->where($where)
            ->paginate($limit);
        $res = convert_obj($res);
        foreach ($res->data as $v)
        {
            self::single($v);
        }
        return $res;
    }

    public static function findById($id)
    {
        $res = self::with(['articleType'])
            ->find($id);
        if (empty($res)) {
            return ;
        }
        self::single($res);
        ArticleTypeModel::single($res->article_type);
        return $res;
    }
}