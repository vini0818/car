<?php

namespace app\admin\controller\user;

use app\common\controller\Backend;
use app\common\model\Dict;

/**
 * 用户信息管理
 *
 * @icon fa fa-user
 */
class User extends Backend {

    /**
     * User模型对象
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('User');
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
            $sex_config = Dict::getMagicConfig('sex');
            $mem_card_config = Dict::getMagicConfig('mem_card');
            $deposit_config = Dict::getMagicConfig('deposit_status');
            foreach ($list as $k => &$v) {
                $v['sex_name'] = $sex_config[$v['sex']]['name'];
                $v['deposit_status'] = $deposit_config[$v['deposit_status']]['name'];
                $v['member_card_status'] = $mem_card_config[$v['member_card_status']]['name'];
            }

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

    /**
     * Selectpage搜索
     * 
     * @internal
     */
    public function selectpage() {
        return parent::selectpage();
    }

}
