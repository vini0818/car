<?php

namespace app\admin\model;

use think\Model;

class Order extends Model {

    // 表名
    protected $table = 'order';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    // 追加属性
    protected $append = [
    ];

    public function user() {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,phone')->bind(['user_name' => 'nickname', 'user_phone' => 'phone']);
    }

    public function village() {
        return $this->belongsTo('Village', 'village_id', 'id')->field('id,name')->bind(['village_name' => 'name']);
    }

}
