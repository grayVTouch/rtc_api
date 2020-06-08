<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/17
 * Time: 14:03
 */

namespace App\Customize\v1\model;


use function core\convert_obj;

class ImageModel extends Model
{
    protected $table = 'rtc_image';

    public static function image($position)
    {
        $res = self::where('position' , $position)
            ->get();
        self::multiple($res);
        return $res;
    }
}