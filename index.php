<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      入口文件
 *      $Id: index.php 2014-04-02 17:56:25 codejm $
 */

define('WEB_ROOT', dirname(__FILE__).'/');

// 加载配置文件
require WEB_ROOT.'config/config.php';
require CORE_PATH.'route.php';

// 路由
$route = new Route();
spl_autoload_register(array($route, 'autoload'));
// start the application
$route->parseURL();

exit;

?>
