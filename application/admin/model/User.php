<?php

namespace app\admin\model;

use think\Model;

class User extends Model {

    // 表名
    protected $table = 'user';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime'; // date/datetime字符串类型，格式由dateFormat指定；其它时间戳格式
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    //类型转换
    protected $type = [
        'create_time' => 'datetime'
    ];

}
