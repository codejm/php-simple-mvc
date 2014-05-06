<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      配置文件
 *      $Id: config.php 2014-05-06 11:18:41 codejm $
 */

//session_start();
error_reporting(E_ALL);
// 关闭所有PHP错误报告
//error_reporting(0);
//ini_set("display_errors", 1);

// APP INFO
define('APPNAME', '房产客户端app接口');
define('URL', 'http://www.house.com/api/');
define('CHILDDIR', '0');
define('R_URL', 'http://www.house.com/api/assets/');

// DATA INFO
// 新后台数据库
define('DB_TYPE', 'mysql');
define('DB_HOST', '10.10.16.104');
define('DB_NAME', 'house');
define('DB_USER', 'houseU');
define('DB_PASS', 'dw12Eqwe6');
define('DB_CHARSET', 'utf8');
// DEDE
define('DB_TYPE_DEDE', 'mysql');
define('DB_HOST_DEDE', '10.10.16.104');
define('DB_NAME_DEDE', 'dedecms_mobile');
define('DB_USER_DEDE', 'kehuduan');
define('DB_PASS_DEDE', 'FukkJh8823');
define('DB_CHARSET_DEDE', 'utf8');
// 房产数据库
//10.10.26.129;uid=housesql;pwd=newhouse_sqlsa!@#$;database=house
define('DB_TYPE_HOUSE', 'dblib');
define('DB_HOST_HOUSE', '10.10.26.129');
define('DB_NAME_HOUSE', 'house');
define('DB_USER_HOUSE', 'housesql');
define('DB_PASS_HOUSE', 'newhouse_sqlsa!@#$');

// 业主论坛数据库
//$con=mysql_connect('10.10.16.40','ultrax2','ultrax20forYzR');
//mysql_select_db("ultrax2");
define('DB_TYPE_YEZHU', 'mysql');
define('DB_HOST_YEZHU', '10.10.16.40');
define('DB_NAME_YEZHU', 'ultrax2');
define('DB_USER_YEZHU', 'ultrax2');
define('DB_PASS_YEZHU', 'ultrax20forYzR');
define('DB_CHARSET_YEZHU', 'gbk');
define('SMSAPI', 'http://101.227.252.127:8181/api/mt.php?eid=110015&key=c2ab15a0aececa0751b23f340f6537e1&mobile={mobile}&message={message}');

// cache INFO
$cache_config = array(
    'type' => 1,
    'memcache_compressed' => 0,
    'host' => '10.10.16.38',
    'port' => 11211
);
define('CACHE_PRE', 'house_app_');

// VIEW INFO
define('PATH_VIEWS', 'application/views/');
define('PATH_VIEW_FILE_TYPE', '.html');

// DIR
define('CORE_PATH', WEB_ROOT . 'application/core/');
define('APP_PATH', WEB_ROOT . 'application/');
define('UPLOAD_DIR', WEB_ROOT . 'data/');
define('DEFAULT_CONTROLLER', 'site');
define('DEFAULT_ACTION', 'index');

// 加载 Composer autoload
if (file_exists(WEB_ROOT.'vendor/autoload.php')) {
    require WEB_ROOT.'vendor/autoload.php';
}
