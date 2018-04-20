<?php

namespace app\admin\model;

use think\Model;

class Carplace extends Model {

    // 表名
    protected $table = 'carplace';
    //自动完成
    protected $auto = ['md5', 'village_id'];
    protected $insert = ['is_using' => 0];
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'date'; // date/datetime字符串类型，格式由dateFormat指定；其它时间戳格式
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = false;

    protected function setMd5Attr($value, $data) {
        return md5($data['gprs_id'] . $data['door_id']);
    }

    protected function setVillageIdAttr($value, $data) {
        return db('carport')->where('gprs_id', $data['gprs_id'])->value('village_id');
    }

    public function village() {
        return $this->belongsTo('Village', 'village_id', 'id')->field('id,name')->bind(['village_name' => 'name']);
    }

}
