<?php

namespace app\admin\model;

use think\Model;

class UserCard extends Model {

    // 表名
    protected $table = 'user_card';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime';
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = 'update_time';

    public function user() {
        return $this->belongsTo('User')->field('id,nickname,member_card,member_card_rfid,member_card_status')->bind(['user_name' => 'nickname', 'member_card', 'member_card_rfid', 'member_card_status']);
    }

    public function setAuditTimeAttr($value, $data) {
        return date('Y-m-d H:i:s');
    }

}
