<?php

namespace app\admin\model;

use think\Model;

class Carport extends Model {

    // 表名
    protected $table = 'carport';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'date'; // date/datetime字符串类型，格式由dateFormat指定；其它时间戳格式
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = false;

    public function village() {
        return $this->belongsTo('Village', 'village_id', 'id')->field('id,name')->bind(['village_name' => 'name']);
    }

}
