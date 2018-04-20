<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 控制台
 *
 * @icon fa fa-dashboard
 * @remark 用于展示当前系统中的统计数据、统计报表及重要实时数据
 */
class Dashboard extends Backend {

    /**
     * 查看
     */
    public function index() {
        $data = $this->realtimeStatistics();
        $this->view->assign($data);

        return $this->view->fetch();
    }

    /**
     * 平台数据实时统计
     * 更新频率：每五分钟更新一次
     * 
     * @return array 
     */
    private function realtimeStatistics() {
        if (cache(config('cache_platform_data'))) {
            return cache(config('cache_platform_data'));
        }
        /* PART-1 总数据 */
        //总会员数
        $total_user = db('user')->count('id');
        //月活跃用户数
        $month_active_user = db('user')->where(array('update_time' => array('>=', date('Y-m-01 00:00:00'))))->count('id');
        //总订单数
        $total_order = db('order')->count('id');
        //总金额
        $total_order_amount = db('order')->sum('total_fee');

        /* PART-2 订单随时间变化曲线 */
        //成交数、订单数
        $seventtime = \fast\Date::unixtime('day', -7);
        $paylist = $createlist = [];
        for ($i = 0; $i < 7; $i++) {
            $day = date("Y-m-d", $seventtime + ($i * 86400));
            $createlist[$day] = mt_rand(20, 200);
            $paylist[$day] = mt_rand(1, mt_rand(1, $createlist[$day]));
        }

        /* PART-2 当天数据 */
        //今日注册
        $todayusersignup = 430;
        //今日登录
        $todayuserlogin = 321;
        //今日订单
        $todayorder = 1;
        //未处理订单
        $unsettleorder = 1;
        //七日增长
        $sevendnu = '50%';
        //七日活跃
        $sevendau = '33%';

        $hooks = config('addons.hooks');
        $uploadmode = isset($hooks['upload_config_init']) && $hooks['upload_config_init'] ? implode(',', $hooks['upload_config_init']) : 'local';
        $data = [
            'totaluser'        => $total_user,
            'monthactiveuser'  => $month_active_user,
            'totalorder'       => $total_order,
            'totalorderamount' => $total_order_amount,
            'todayuserlogin'   => $todayuserlogin,
            'todayusersignup'  => $todayusersignup,
            'todayorder'       => $todayorder,
            'unsettleorder'    => $unsettleorder,
            'sevendnu'         => $sevendnu,
            'sevendau'         => $sevendau,
            'paylist'          => $paylist,
            'createlist'       => $createlist,
            'uploadmode'       => $uploadmode
        ];
        cache(config('cache_platform_data'), $data, ['expire' => 300]);
        return $data;
    }

}
