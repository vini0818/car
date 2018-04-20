<?php

namespace app\admin\validate;

use think\Validate;

class Carplace extends Validate {

    /**
     * 验证规则
     */
    protected $rule = [
        'place_code' => 'require|unique:carplace,place_code',
        'gprs_id'    => 'require',
        'door_id'    => 'require|unique:carplace,door_id',
        'status'     => 'require|in:0,1'
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
        'add'  => ['gprs_id', 'door_id', 'place_code', 'status'],
        'edit' => ['gprs_id', 'door_id', 'place_code'],
    ];

    public function __construct(array $rules = [], $message = [], $field = []) {
        $this->field = [
            'place_code' => __('place_code'),
            'status'     => __('status')
        ];
        parent::__construct($rules, $message, $field);
    }

    public function updateRule($id) {
        $this->rule([
            'place_code' => 'require|unique:carplace,place_code,' . $id,
            'door_id'    => 'require|unique:carplace,door_id,' . $id
        ]);
    }

}
