<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      Model 父类
 *      $Id: Models.php 2014-04-08 14:58:54 codejm $
 */

class BaseModels {

    /**
     * 数据初始化
     *
     */
    function __get($name) {
        $options = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ, PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING);
        if($name == 'db') {
            // 新后台
            $this->$name = new PDO(DB_TYPE . ':host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, $options);
            $this->$name->exec('SET CHARACTER SET '.DB_CHARSET);
        } else if($name == 'db_house') {
            // 房产
            $conf_path = WEB_ROOT.'config/freetds.conf';
            putenv("FREETDSCONF=$conf_path");
            $this->$name = new PDO(DB_TYPE_HOUSE . ':host=' . DB_HOST_HOUSE . ';dbname=' . DB_NAME_HOUSE, DB_USER_HOUSE, DB_PASS_HOUSE, $options);
        } else if($name == 'db_yezhu') {
            // 业主
            $this->$name = new PDO(DB_TYPE_YEZHU . ':host=' . DB_HOST_YEZHU . ';dbname=' . DB_NAME_YEZHU, DB_USER_YEZHU, DB_PASS_YEZHU, $options);
            $this->$name->exec('SET CHARACTER SET '.DB_CHARSET_YEZHU);
        } else if($name  == 'db_dede') {
            // DEDE
            $this->$name = new PDO(DB_TYPE_DEDE. ':host=' . DB_HOST_DEDE. ';dbname=' . DB_NAME_DEDE, DB_USER_DEDE, DB_PASS_DEDE, $options);
            $this->$name->exec('SET CHARACTER SET '.DB_CHARSET_DEDE);
        } else if($name  == 'cache') {
            global $cache_config;
            $c = new Cache();
            $this->$name = $c::create($cache_config);
        }
        return $this->$name;
    }
}

?>
