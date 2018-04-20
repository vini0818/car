<?php

namespace app\admin\model;

use think\Model;

class RechargeRecord extends Model {

    // 表名
    protected $table = 'recharge_record';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    // 追加属性
    protected $append = [
    ];

    public function user() {
        return $this->belongsTo('user', 'user_id', 'id')->field('id,phone')->bind(['user_phone' => 'phone']);
    }

    public function rechargeCard() {
        return $this->belongsTo('recharge_card', 'recharge_card_id', 'id')->field('id,name')->bind(['recharge_card_name' => 'name']);
    }

}
