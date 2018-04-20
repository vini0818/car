<?php

namespace app\admin\controller;

use app\common\controller\Backend;
use app\common\model\Dict;

/**
 * 系统预警消息
 *
 * @icon fa fa-warning
 */
class Warning extends Backend {

    /**
     * Warning模型对象
     */
    protected $model = null;

    /**
     * Multi方法可批量修改的字段
     */
    protected $multiFields = 'is_read,status';

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Warning');
        $this->view->assign('typeData', Dict::getMagicData('warn_type'));
    }

    /**
     * 查看
     */
    public function index() {
        //设置过滤方法
        $this->request->filter(['strip_tags']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('pkey_name')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();
            $total = $this->model
                    ->where($where)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $type_config = Dict::getMagicConfig('warn_type');
            foreach ($list as $k => &$v) {
                $v['type_name'] = $type_config[$v['type']]['name'];
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 批量更新
     */
    public function multi($ids = "") {
        $ids = $ids ? $ids : $this->request->param("ids");
        if ($ids) {
            if ($this->request->has('params')) {
                parse_str($this->request->post("params"), $values);
                $values = array_intersect_key($values, array_flip(is_array($this->multiFields) ? $this->multiFields : explode(',', $this->multiFields)));
                if ($values) {
                    $this->multiAppend($values);
                    $adminIds = $this->getDataLimitAdminIds();
                    if (is_array($adminIds)) {
                        $this->model->where($this->dataLimitField, 'in', $adminIds);
                    }
                    $count = $this->model->where($this->model->getPk(), 'in', $ids)->update($values);
                    if ($count) {
                        $this->success();
                    } else {
                        $this->error(__('No rows were updated'));
                    }
                } else {
                    $this->error(__('You have no permission'));
                }
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    private function multiAppend(&$data) {
        if (isset($data['is_read']) && $data['is_read'] == 1) {
            $data['read_time'] = date('Y-m-d H:i:s');
        }
    }

}
