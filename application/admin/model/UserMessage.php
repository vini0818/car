<?php

namespace app\admin\model;

use think\Model;

/**
 * Description of UserMessage
 *
 * @author wyx
 */
class UserMessage extends Model {

    // 表名
    protected $table = 'user_message';
    //自动完成
    protected $insert = ['is_read' => 0];
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = 'datetime'; // date/datetime字符串类型，格式由dateFormat指定；其它时间戳格式
    protected $dateFormat = 'Y-m-d H:i:s';
    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

}
