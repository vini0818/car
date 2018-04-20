<?php

namespace app\admin\model;

use think\Model;

class Car extends Model {

    // 表名
    protected $table = 'car';
    //自动完成
    protected $auto = ['gprs_id', 'village_id'];
    protected $insert = ['is_using' => 0];
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime'; // date/datetime字符串类型，格式由dateFormat指定；其它时间戳格式
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    protected function setGprsIdAttr($value, $data) {
        return db('carplace')->where('door_id', $data['door_id'])->value('gprs_id');
    }

    protected function setVillageIdAttr($value, $data) {
        return db('carplace')->where('door_id', $data['door_id'])->value('village_id');
    }

    public function village() {
        return $this->belongsTo('Village', 'village_id', 'id')->field('id,name')->bind(['village_name' => 'name']);
    }

}
