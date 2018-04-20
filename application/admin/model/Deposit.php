<?php

namespace app\admin\model;

use think\Model;

/**
 * 用户押金
 *
 * @author wyx
 */
class Deposit extends Model {

    // 表名
    protected $table = 'trade';

    public function user() {
        return $this->belongsTo('User', 'user_id', 'id')->field('id,nickname,phone')->bind(['user_name' => 'nickname', 'user_phone' => 'phone']);
    }

    public function tradeRefund() {
        return $this->belongsTo('Trade_refund', 'trade_no', 'trade_no')->field('trade_no,refund_fee,create_time,end_time,status,err_code_des')->bind(['refund_fee', 'refund_create_time' => 'create_time', 'refund_end_time' => 'end_time', 'refund_status' => 'status', 'err_code_des']);
    }

    public function getTotalFeeAttr($value, $data) {
        return sprintf('%.2f', $value / 100);
    }

}
