<?php
/**
 * Created by PhpStorm.
 * User: grayVTouch
 * Date: 2020/4/15
 * Time: 11:32
 */

namespace App\Customize\v1\model;

use function core\convert_obj;
use Exception;
use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    // 不使用 默认时间戳字段
    public $timestamps = false;

    public static function multiple($list)
    {
        foreach ($list as &$v)
        {
            static::single($v);
        }
    }

    public static function single($m = null)
    {
        if (empty($m)) {
            return ;
        }
        if (!is_object($m)) {
            throw new Exception('不支持的类型');
        }
    }

    // 更新
    public static function updateById($id , array $param = [])
    {
        return static::where('id' , $id)
            ->update($param);
    }

    public static function getAll()
    {

        $res = static::all();
        $res = convert_obj($res);
        static::multiple($res);
        return $res;
    }

    public static function findById($id)
    {
        $res = static::find($id);
        if (empty($res)) {
            return ;
        }
        $res = convert_obj($res);
        static::single($res);
        return $res;
    }

    public static function delById($id)
    {
        return static::where('id' , $id)
            ->delete();
    }
}