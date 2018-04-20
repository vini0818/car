<?php

namespace app\admin\validate;

use think\Validate;

class Carport extends Validate {

    /**
     * 验证规则
     */
    protected $rule = [
        'gprs_id'    => 'require|unique:carport,gprs_id',
        'village_id' => 'require',
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
        'add'  => ['gprs_id', 'village_id', 'status'],
        'edit' => ['gprs_id', 'village_id'],
    ];

    public function __construct(array $rules = [], $message = [], $field = []) {
        $this->field = [
            'village_id' => __('village_id'),
            'status'     => __('status')
        ];
        parent::__construct($rules, $message, $field);
    }

    public function updateRule($id) {
        $this->rule([
            'gprs_id' => 'require|unique:carport,gprs_id,' . $id
        ]);
    }

}
