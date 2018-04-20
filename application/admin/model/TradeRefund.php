<?php

namespace app\admin\model;

use think\Model;

/**
 * Description of TradeRefund
 *
 * @author wyx
 */
class TradeRefund extends Model {

    // 表名
    protected $table = 'trade_refund';

    public function getRefundFeeAttr($value, $data) {
        return sprintf('%.2f', $value / 100);
    }

}
