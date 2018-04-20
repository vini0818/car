<?php

namespace app\admin\validate;

use think\Validate;

class Car extends Validate {

    /**
     * 验证规则
     */
    protected $rule = [
        'door_id'      => 'require',
        'car_id'       => 'require|unique:car,car_id',
        'bluetooth_id' => 'require|unique:car,bluetooth_id',
        'status'       => 'require|in:0,1'
    ];

    /**
     * 提示消息
     */
    protected $message = [
    ];

    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['door_id', 'car_id', 'bluetooth_id', 'status'],
        'edit' => ['door_id', 'car_id', 'bluetooth_id'],
    ];

    public function __construct(array $rules = [], $message = [], $field = []) {
        $this->field = [
            'bluetooth_id' => __('Bluetooth_id'),
            'status'       => __('status'),
        ];
        parent::__construct($rules, $message, $field);
    }

    public function updateRule($id) {
        $this->rule([
            'car_id'       => 'require|unique:car,car_id,' . $id,
            'bluetooth_id' => 'require|unique:car,bluetooth_id,' . $id
        ]);
    }

}
