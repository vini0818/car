<?php

/**
 * 本地API调用类
 * @author vini
 */

namespace my;

use think\Log;

class Api {

    //接口参数配置常量
    const PARAM_SOURCE = 3; //请求源：管理后台  1-微信 2-APP 3-管理后台
    //接口地址
    const ORDER_CLOSE = '/Admin/closeOrder'; //强制结束订单
    const ORDER_REFUND = '/Admin/refundOrder'; //订单退款
    const DEPOSIT_REFUND = '/Admin/refundDeposit'; //押金退款

    private static $API_URL_PREFIX = ''; //本地API地址
    public $errCode = 100;
    public $errMsg = "http error";

    public function __construct() {
        self::$API_URL_PREFIX = config('api_url');
    }

    /*     * ********************************************************************** */
    //                               PART-1 业务接口
    /*     * *********************************************************************** */

    /**
     * 强制结束订单
     * @param string  $id      用户订单编号
     * @param boolean $is_pay  是否需要支付标识 true-是 false-否
     * @return boolean  
     */
    public function closeOrder($id, $is_pay = false) {
        $param['order_no'] = $id;
        $param['is_pay'] = $is_pay;
        $param['source'] = self::PARAM_SOURCE;
        $result = $this->http_post(self::$API_URL_PREFIX . self::ORDER_CLOSE, $param);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || (int) $json['code'] !== 0) {
                $this->errCode = $json['code'];
                $this->errMsg = $json['message'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 订单退款
     * 
     * @param int   $user_id  退款账户ID
     * @param int   $order_id 退款订单ID
     * @param float $amount   退款金额（单位：元）
     * @return boolean
     */
    public function refundOrder($user_id, $order_id, $amount) {
        $param['user_id'] = $user_id;
        $param['order_id'] = $order_id;
        $param['amount'] = $amount;
        $param['source'] = self::PARAM_SOURCE;
        $result = $this->http_post(self::$API_URL_PREFIX . self::ORDER_REFUND, $param);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || (int) $json['code'] !== 0) {
                $this->errCode = $json['code'];
                $this->errMsg = $json['message'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 押金退款
     * 
     * @param int   $user_id  退款账户ID
     * @param int   $trade_no 原充值交易订单号
     * @param float $amount   退款金额（单位：元）
     * @return boolean
     */
    public function refundDeposit($user_id, $trade_no, $amount) {
        $param['user_id'] = $user_id;
        $param['trade_no'] = $trade_no;
        $param['amount'] = $amount;
        $param['source'] = self::PARAM_SOURCE;
        $result = $this->http_post(self::$API_URL_PREFIX . self::DEPOSIT_REFUND, $param);
        if ($result) {
            $json = json_decode($result, true);
            if (!$json || (int) $json['code'] !== 0) {
                $this->errCode = $json['code'];
                $this->errMsg = $json['message'];
                return false;
            }
            return true;
        }
        return false;
    }

    /**
     * 日志记录，可被重载。
     * @param mixed $log 输入日志
     * @return mixed
     */
    protected function log($log, $type = LOG::NOTICE) {
        Log::record(print_r($log, TRUE), $type);
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url, $param, $post_file = false, $timeout = false) {
        $oCurl = curl_init();
        if (stripos($url, "https://") !== FALSE) {
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
        }
        if (is_string($param) || $post_file) {
            $strPOST = $param;
        } else {
            $strPOST = http_build_query($param);
        }
        curl_setopt($oCurl, CURLOPT_URL, $url);
        curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($oCurl, CURLOPT_POST, true);
        curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
        if ($timeout > 2) {
            curl_setopt($oCurl, CURLOPT_TIMEOUT, $timeout);   //只需要设置一个秒的数量就可以
        }
        $this->log("POST URL: " . $url . "\t" . $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
        $this->log("POST RESULT: " . $sContent);
        if (intval($aStatus["http_code"]) == 200) {
            curl_close($oCurl);
            return $sContent;
        } else {
            $error = curl_errno($oCurl);
            curl_close($oCurl);
            $this->log($aStatus, LOG::ERROR);
            $this->log("curl出错，错误码:$error", LOG::ERROR);
            return false;
        }
    }

}
