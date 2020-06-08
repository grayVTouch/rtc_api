<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 14:04
 */

namespace App\Customize\v1\model;


use function core\convert_obj;
use Illuminate\Support\Facades\DB;

class FriendCircleModel extends Model
{
    protected $table = 'rtc_friend_circle';

    public static function myFriendCircle($user_id , $user_ids , $limit_id , $limit)
    {
//        $sql = <<<EOT
//        select * from rtc_friend_circle as f where f.user_id in (1,2,3,4) and
//          -- 不看谁
//          not exists (select id from rtc_friend_circle_visible where type = 0 and user_id = 1 and relative_user_id = f.user_id) and
//          -- 不让谁看
//          not exists (select id from rtc_friend_circle_visible where type = 1 and user_id = f.user_id and relative_user_id = 1)
//          -- 朋友圈可见性时间限定
//EOT;
        $res = self::whereIn('user_id' , $user_ids);
        if (!empty($limit_id)) {
            $res->where('id' , '<' , $limit_id);
        }
        $res = $res->whereNotExists(function($query) use($user_id){
                $query->select('id')
                    ->from('rtc_friend_circle_visible')
                    ->whereRaw("
                        user_id = {$user_id} and 
                        relation_user_id = rtc_friend_circle.user_id and
                        type = 0
                    ");
            })
            ->whereNotExists(function($query) use($user_id){
                $query->select('id')
                    ->from('rtc_friend_circle_visible')
                    ->whereRaw("
                        user_id = rtc_friend_circle.user_id and 
                        relation_user_id = {$user_id} and
                        type = 1
                    ");
            })
            ->orderBy('id' , 'desc')
            ->limit($limit)
            ->get();
        $res = convert_obj($res);
        foreach ($res as $v)
        {
            self::single($v);
        }
        return $res;
    }

    public static function getByUserId($user_id , $limit = 20)
    {
        $res = self::where('user_id' , $user_id)
            ->select(DB::raw('*,date_format(create_time , "%Y") as year'))
            ->paginate($limit);
        $res = convert_obj($res);
        foreach ($res->data as $v)
        {
            self::single($v);
        }
        return $res;
    }
}