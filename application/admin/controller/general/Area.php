<?php

namespace app\admin\controller\general;

use app\common\controller\Backend;

/**
 * 地区管理
 *
 * @icon fa fa-sitemap
 * @remark 省市区详细信息管理（国内版）：单表树状结构（通过父ID和Level层级控制）
 */
class Area extends Backend {

    /**
     * Area模型对象
     */
    protected $model = null;

    public function _initialize() {
        parent::_initialize();
        $this->model = model('Area');
    }

    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
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
            $filter = json_decode($this->request->param('filter'), TRUE);
            $extWhere = isset($filter['pid']) ? array() : array('pid' => 0);
            list($where, $sort, $order, $offset, $limit) = $this->buildparams(null, null, $extWhere);
            $total = $this->model
                    ->where($where)
                    ->count();

            $list = $this->model
                    ->where($where)
                    ->order($sort, $order)
                    ->limit($offset, $limit)
                    ->select();

            $result = array("total" => $total, "rows" => $list);

            return json($result);
        }
        return $this->view->fetch();
    }

}
