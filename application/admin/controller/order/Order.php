<?php

namespace app\admin\controller\order;

use think\Cache;
use app\common\controller\Backend;
use app\common\model\Dict;
use my\Api;

/**
 * 用户订单管理
 *
 * @icon fa fa-list
 */
class Order extends Backend {

    /**
     * Order模型对象
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Order');
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
                    ->with('user,village')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $status_config = Dict::getMagicConfig('order_status');
            foreach ($list as $k => &$v) {
                $v['status_color'] = $status_config[$v['status']]['color'];
                $v['status'] = $status_config[$v['status']]['name'];
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 结束订单（强制）
     * 
     * @icon fa fa-stop-circle-o
     * @remark 当前默认结束时不需要支付
     */
    public function close($ids = "") {
        if ($ids) {
            if (strpos($ids, ',') !== false) {
                $this->error(__('Deal one by one'));
            } else {
                $Api = new Api();
                $result = $Api->closeOrder($ids, false);
                if ($result) {
                    $this->success();
                } else {
                    $this->error(__('Operation failed') . ':' . $Api->errMsg);
                }
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }

    /**
     * 订单退款
     * 
     * @icon fa fa-yen
     * @remark 允许部分退款，一个订单只能退款一次
     */
    public function refund($ids = "") {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $row['status'] = Dict::getMagicTitle('order_status', $row['status']);
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params['user_id'] && $params['id'] && $params['refund_fee']) {
                $Api = new Api();
                $result = $Api->refundOrder($params['user_id'], $params['id'], $params['refund_fee']);
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

    /**
     * 编辑
     * 
     * @remark 修改订单价格：只允许修改待支付的订单
     */
    public function edit($ids = NULL) {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($row['status'] != Dict::ORDER_STATUS_UNPAY) {
            $this->error(__('You have no permission') . ': 只允许修改待支付订单价格');
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $editValidate = \think\Loader::validate($name);
                        if (method_exists($editValidate, 'updateRule')) {
                            $editValidate->updateRule($row->id);
                        }
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->save($params);
                    if ($result !== false) {
                        //修改待支付订单价格
                        if ($row['total_fee'] != $params['total_fee']) {
                            $options = config('memcache');
                            $user_session_key = create_user_session_key($row['user_id']);
                            $session_user = Cache::connect($options)->get($user_session_key);
                            $session_user['total_fee'] = number_format($params['total_fee'], 2); //总费用（单位：元）
                            $session_user['balance_fee'] = number_format($params['balance_fee'], 2); //余额支付费用
                            $session_user['cash_fee'] = number_format($params['cash_fee'], 2); //余额支付费用
                            Cache::connect($options)->set($user_session_key, $session_user);
                        }
                        $this->success();
                    } else {
                        $this->error($row->getError());
                    }
                } catch (\think\exception\PDOException $e) {
                    $this->error($e->getMessage());
                }
            }
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 详情
     */
    public function detail($ids) {
        $row = $this->model->get(['id' => $ids]);
        if (!$row)
            $this->error(__('No Results were found'));
        unset($row->id);
        unset($row->user_id);
        unset($row->village_id);
        $row->status = Dict::getMagicTitle('order_status', $row->status);
        $row->refund_status = Dict::getMagicTitle('refund_status', $row->refund_status);
        $this->view->assign("row", $row->toArray());
        return $this->view->fetch();
    }

}
