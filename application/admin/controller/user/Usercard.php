<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use app\common\model\Dict;

/**
 * 实物卡申请记录管理
 *
 * @icon fa fa-vcard
 */
class Usercard extends Backend {

    /**
     * UserCard模型对象
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('UserCard');
        $this->view->assign('auditData', Dict::getMagicData('mem_card_audit', '1'));
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
                    ->with('user')
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();
            $status_config = Dict::getMagicConfig('mem_card_audit');
            foreach ($list as $k => &$v) {
                $v['status'] = $status_config[$v['status']]['name'];
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = NULL) {
        $row = $this->model->get($ids);
        if (!$row)
            $this->error(__('No Results were found'));
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $params['member_card_status'] = Dict::MEM_CARD_SENDING;
                    $result = $row->allowField(true)->together(['user' => ['member_card_status']])->save($params);
                    if ($result !== false) {
                        $this->editAfter($params);
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
     * 邮寄操作成功：用户消息通知
     */
    private function editAfter($data) {
        $UserMessage = model('UserMessage');
        $UserMessage->user_id = $data['user_id'];
        $UserMessage->type = Dict::MESSAGE_TYPE_CARD_SEND;
        $UserMessage->title = '';
        $UserMessage->content = '您的实物卡已寄出，快递公司：' . $data['express'] . '，快递单号：' . $data['express_number'];
        $UserMessage->save();
    }

    /**
     * 审核
     */
    public function audit($ids = NULL) {
        $row = $this->model->get($ids, 'user');
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds)) {
            if (!in_array($row[$this->dataLimitField], $adminIds)) {
                $this->error(__('You have no permission'));
            }
        }
        if ($this->request->isPost()) {
            $params = $this->request->post("row/a");
            if ($params) {
                try {
                    //是否采用模型验证
                    if ($this->modelValidate) {
                        $name = basename(str_replace('\\', '/', get_class($this->model)));
                        $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : true) : $this->modelValidate;
                        $row->validate($validate);
                    }
                    $result = $row->allowField(true)->together(['user' => ['member_card_rfid']])->save($params);
                    if ($result !== false) {
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

}
