<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace my;

use think\Log;

/**
 * Description of Webmsg
 *
 * @author wyx
 */
class Webmsg {

    static protected $push_api_url = ''; //推送的url地址：使用自己的服务器地址

    public function __construct() {
        self::$push_api_url = 'http://' . MAIN_DOMAIN . ':2121/';
    }

    /**
     * 
     * @param string $msg_content   推送的消息内容
     * @param string $to_uid        指明给谁推送，为空表示向所有在线用户推送
     * @param string $msg_type      推送的消息类型，在workman服务器端会根据该消息类型进行处理
     */
    public function send($msg_content, $to_uid = '', $msg_type = 'publish') {
        $param = array(
            "type"    => $msg_type,
            "content" => $msg_content,
            "to"      => $to_uid,
        );

        $result = $this->http_post(self::$push_api_url, $param);
        if ($result === 'ok') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 日志记录，可被重载。
     * @param mixed $log 输入日志
     * @return mixed
     */
    protected function log($log) {
        Log::record(print_r($log, TRUE), LOG::INFO, true);
    }

    /**
     * POST 请求
     * @param string $url
     * @param array $param
     * @param boolean $post_file 是否文件上传
     * @return string content
     */
    private function http_post($url, $param, $post_file = false) {
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
        $this->log("POST DATA: " . $url . "?" . $strPOST);
        $sContent = curl_exec($oCurl);
        $aStatus = curl_getinfo($oCurl);
//        $this->log($aStatus);
        $this->log("POST RETURN: " . $sContent);
        if (intval($aStatus["http_code"]) == 200) {
            curl_close($oCurl);
            return $sContent;
        } else {
            $error = curl_errno($oCurl);
            $this->log("curl出错，错误码:$error");
            curl_close($oCurl);
            return false;
        }
    }

}
