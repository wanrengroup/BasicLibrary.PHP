<?php
return array(
    //'配置项'=>'配置值'

    //数据库配置信息
    'DB_TYPE' => 'mysql',     // 数据库类型
    'DB_HOST' => '127.0.0.1', // 服务器地址
    'DB_NAME' => 'my_tp8_study',  // 数据库名
    'DB_USER' => 'root',      // 用户名
    'DB_PWD' => '123456',     // 密码
    'DB_PORT' => 3306,        // 端口
    'DB_PARAMS' => array(),   // 数据库连接参数
    'DB_PREFIX' => 'm_',      // 数据库表前缀
    'DB_CHARSET' => 'utf8',   // 字符集
    'DB_DEBUG' => TRUE,       // 数据库调试模式 开启后可以记录SQL日志

    //数据库配置2(DNS方式,无法指定数据库表前缀)
    'DB_CONFIG2' => 'mysql://root:123456@localhost:3306/thinkphp#utf8',

    'DB_CONFIG3' => array(
        'db_type' => 'mysql',
        'db_user' => 'root',
        'db_pwd' => '123456',
        'db_host' => 'localhost',
        'db_port' => '3306',
        'db_name' => 'my_tp8_biz_study',
        'DB_PREFIX' => 'w_', // 数据库表前缀
        'db_charset' => 'utf8',
    ),

    'DB_CONFIG4' => array(
        'db_type' => 'mysql',
        'db_user' => 'onlytest.chuangzao.top',
        'db_pwd' => 'ZWjAmPc7wCxarXTY',
        'db_host' => '39.105.4.205',
        'db_port' => '3306',
        'db_name' => 'onlytest.chuangzao.top',
        'DB_PREFIX' => 'w_', // 数据库表前缀
        'db_charset' => 'utf8',
    ),
);