<?php

namespace app\admin\controller\car;

use app\common\controller\Backend;
use app\common\model\Dict;

/**
 * 车辆信息管理
 *
 * @icon fa fa-car
 * @remark 车辆管理：一个车位对应有一辆车
 */
class Car extends Backend {

    protected $modelValidate = true; //开启字段验证
    protected $modelSceneValidate = true; //开启场景验证

    /**
     * Car模型对象
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Car');
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个方法
     * 因此在当前控制器中可不用编写增删改查的代码,如果需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

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
            $status_config = Dict::getMagicConfig('status');
            foreach ($list as $k => &$v) {
                $v['status'] = $status_config[$v['status']]['name'];
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
        if (isset($data['status'])) {
            if ($data['status'] == Dict::STATUS_NORMAL) {
                $data['enable_time'] = date('Y-m-d H:i:s');
            } else if ($data['status'] == Dict::STATUS_DISABLED) {
                $data['disable_time'] = date('Y-m-d H:i:s');
            }
        }
    }

}
