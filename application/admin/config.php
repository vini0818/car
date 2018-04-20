<?php

//配置文件
return [
    'url_common_param'       => true,
    'url_html_suffix'        => '',
    'controller_auto_search' => true,
    // +----------------------------------------------------------------------
    // | 自定义设置
    // +----------------------------------------------------------------------
    'cache_platform_data'    => 'platform_data',
    'api_url'                => 'http://car.com/api/index.php',
    'api_cache_path'         => array(
        'data' => ROOT_PATH . '../api/Runtime/Data/',
    ),
    'memcache'               => array(
        'type'   => 'Memcache', // 驱动方式
        'host'   => '10.10.10.3', //Memcache服务器ip
        'port'   => 11211, //Memcache服务器端口号
        'expire' => 0, // 缓存有效期 0表示永久缓存
    ), // 用户信息缓存设置
    'msg_server_url'         => 'http://wx.wansmart.cn:2120', // 实际部署web-msg-sender的服务器地址
];
