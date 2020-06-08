<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:03
 */

namespace App\Customize\v1\model;


use function core\convert_obj;

class AppModel extends Model
{
    protected $table = 'rtc_app';

//    public static function app($filter = [] , $limit = 20)
    public static function app($limit = 20)
    {
        $where = [

        ];
        $res = self::where($where)
            ->limit($limit)
            ->get();
//        $res = convert_obj($res);
//        foreach ($res->data as $v)
//        {
//            self::single($v);
//        }
        return $res;
    }
}