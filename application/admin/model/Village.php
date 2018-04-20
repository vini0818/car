<?php

namespace app\admin\model;

use think\Model;

class Village extends Model {

    // 表名
    protected $table = 'village';
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'date'; // date/datetime字符串类型，格式由dateFormat指定；其它时间戳格式
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = false;

    public function province() {
        return $this->belongsTo('Area', 'province_id', 'id')->field('id,name')->bind(['province_name' => 'name']);
    }

    public function city() {
        return $this->belongsTo('Area', 'city_id', 'id')->field('id,name')->bind(['city_name' => 'name']);
    }

    public function area() {
        return $this->belongsTo('Area', 'area_id', 'id')->field('id,name')->bind(['area_name' => 'name']);
    }

    public function partner() {
        return $this->belongsTo('Partner', 'partner_id', 'id')->field('id,name')->bind(['partner_name' => 'name']);
    }

    public function company() {
        return $this->belongsTo('Company', 'company_id', 'id')->field('id,name')->bind(['company_name' => 'name']);
    }

}
