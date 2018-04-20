<?php

namespace app\admin\controller\order;

use app\common\controller\Backend;
use app\common\model\Dict;
use my\Api;

/**
 * 用户押金管理
 *
 * @icon  fa fa-circle-o
 */
class Deposit extends Backend {

    /**
     * Order模型对象
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Deposit');
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
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, array('type' => 1));
            $total = $this->model
                    ->where($where)
                    ->count();

            $list = $this->model
                    ->with('user,trade_refund')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $status_config = Dict::getMagicConfig('trade_status');
            $refund_status_config = Dict::getMagicConfig('refund_status');
            foreach ($list as $k => &$v) {
                $v['status_color'] = $status_config[$v['status']]['color'];
                $v['status'] = $status_config[$v['status']]['name'];
                if (!is_null($v['refund_status'])) {
                    $v['refund_status_color'] = $refund_status_config[$v['refund_status']]['color'];
                    $v['refund_status'] = $refund_status_config[$v['refund_status']]['name'];
                }
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 押金退款
     * 
     * @icon fa fa-yen
     * @remark 允许部分退款，一个订单只能退款一次
     */
    public function refund($ids = "") {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $row['status'] = Dict::getMagicTitle('trade_status', $row['status']);
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params['user_id'] && $params['trade_no'] && $params['refund_fee']) {
                $Api = new Api();
                $result = $Api->refundDeposit($params['user_id'], $params['trade_no'], $params['refund_fee']);
                if ($result) {
                    $this->success();
                } else {
                    $this->error(__('Operation failed') . ':' . $Api->errMsg);
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

}
