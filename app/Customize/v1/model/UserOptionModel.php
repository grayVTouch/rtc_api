<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 11:41
 */

namespace App\Customize\v1\model;


class UserOptionModel extends Model
{
    protected $table = 'rtc_user_option';

    public static function updateByUserId($user_id , array $data = [])
    {
        return self::where('user_id' , $user_id)
            ->update($data);
    }
}