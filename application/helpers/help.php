<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      Help 工具类
 *      $Id: help.php 2014-04-03 11:20:32 codejm $
 */

class Help {

    /**
     * 	stripslashes 过滤
     */
    public static function checkChar($char, $t = '') {
        if (empty($char)) {
           return $t;
        }
        if (get_magic_quotes_gpc() > 0) {
            return $char;
        } else {
            return addslashes($char);
        }
    }

    /**
     * 使用反斜线引用数据 防注入
     */
    public static function daddslashes($string, $force = 0) {
        !defined('MAGIC_QUOTES_GPC') && define('MAGIC_QUOTES_GPC', get_magic_quotes_gpc());
        if (!MAGIC_QUOTES_GPC || $force) {
            if (is_array($string)) {
                foreach ($string as $key => $val) {
                    $string[$key] = daddslashes($val, $force);
                }
            } else {
                $string = addslashes($string);
            }
        }
        return $string;
    }

    /**
     *  获取用户的ip，使用方法 : help:getIp();
     */
    public static function getIp() {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif(isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        return $ip;
    }

    /**
     * 通过新浪接口 获取ip地理位置
     */
    public static function sinaIPApi($ip){
        $add = '未知区域';
        $str = file_get_contents("http://int.dpool.sina.com.cn/iplookup/iplookup.php?ip=".$ip);
        $str = iconv("gbk", "utf-8//IGNORE", $str);
        if(preg_match_all("/[\x{4e00}-\x{9fa5}]+/u",$str,$get))
            $add = implode('', $get[0]);
        return $add;
    }

    /**
     * 获取GET数据 help::getg("name");
     */
    public static function getg($p, $t = "") {
        return isset($_GET[$p]) ? self::daddslashes($_GET[$p], $t) : $t;
    }

    /**
     * 获取 POST 数据 	 help::getp("name");
     */
    public static function getp($p, $t = "") {
        //return isset($_POST[$p]) ? self::daddslashes($_POST[$p], $t) : $t;
        return isset($_REQUEST[$p]) ? self::daddslashes($_REQUEST[$p], $t) : $t;
    }

    /**
     * 获取request参数 help::getPar("name");
     */
    public static function getPar($p, $t = "") {
        return isset($_REQUEST[$p]) ? self::daddslashes($_REQUEST[$p], $t) : $t;
    }

    /**
     * 获取cookie数据 getcookie($p);
     */
    public static function setCookie($key, $value, $time) {
        $key = 'houseApp_'.$key;
        setcookie($key, $value, time()+$time, '/');
    }

    /**
     * 获取cookie数据 getcookie($p);
     */
    public static function getCookie($key, $value = "") {
        $key = 'houseApp_'.$key;
        return isset($_COOKIE[$key]) ? self::checkChar($_COOKIE[$key]) : $value;
    }

    /**
     * 设置Session值
     * @param mixed $key
     * @param mixed $value
     */
    public static function setSession($key, $value) {
        if(empty($value) && isset($_SESSION[$key]))
            unset($_SESSION[$key]);
        else
            $_SESSION[$key] = $value;
    }

    /**
     * 获取Session值
     * @param mixed $key Usually a string, right ?
     * @return mixed
     */
    public static function getSession($key, $value = "") {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return $value;
        }
    }

    /**
     *  curl方式post数据  $arr数组用来设置要post的字段和数值 help::getpost("http://www.123.com",$array);
     *  $array = array('name'=>'good','pass'=>'wrong');
     */
    public static function getpost($URL, $arr) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);      //设置返回信息的内容和方式
        curl_setopt($ch, CURLOPT_POSTFIELDS, $arr);       //发送post数据
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);            //设置30秒超时
        $result = curl_exec($ch);                         //进行数据传输
        curl_close($ch);                                  //关闭
        return $result;
    }

    /**
     * curl 方式 get数据 help::getget('http://www.123.com')
     */
    public static function getget($url) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);            //设置30秒超时
        $result = curl_exec($ch);
        curl_close($ch);
        return $result;
    }

    /* 并行获取数据方法。未测试 */
    public static function curl_gets($urls, $timeout = 1) {
        if (count($urls) <= 0)
            return false;
        $hd_array = array(); //handle array
        foreach ($urls as $k => $v) {
            $h = curl_init();
            curl_setopt($h, CURLOPT_URL, $v);
            curl_setopt($h, CURLOPT_HEADER, 0);
            curl_setopt($h, CURLOPT_TIMEOUT, $timeout);
            curl_setopt($h, CURLOPT_RETURNTRANSFER, 1); //return the image value
            array_push($hd_array, $h);
        }
        $mh = curl_multi_init();
        foreach ($hd_array as $k => $h)
            curl_multi_add_handle($mh, $h);
        $running = null;
        do {
            url_multi_exec($mh, $running);
        } while ($running > 0);
        // get the result and save it in the result ARRAY
        foreach ($hd_array as $k => $h) {
            $result[$k]['info'] = curl_getinfo($h);
            $result[$k]['data'] = curl_multi_getcontent($h);
            curl_multi_remove_handle($mh, $h);
        }
        curl_multi_close($mh);
        return $result;
    }

    /**
     * 格式化日期
     *
     */
    public static function formatDate($time) {
        $now = time();
        if ($now - $time < 60) {
            $fdate = '刚才';
        } elseif ($now - $time < 3600) {
            $fdate = ($now - $time) / 60;
            $fdate = floor($fdate) . '分钟前';
        } elseif ($time > strtotime(date("Y-m-d", time()) . ' 00:00:00')) {
            $fdate = '今天' . date('H:i', $time);
        } elseif ($time <= strtotime(date("Y", time()) . '-12-31 23:59:59')) {
            $fdate = date('m月d日 H:i', $time);
        } else {
            $fdate = date('Y年m月d日 H:i', $time);
        }
        return $fdate;
    }

    /**
     * 获取micro时间，作为程序执行时间检测使用
     */
    public static function get_microtime(){
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    /**
     * 字符编码转换 gbk -> utf8
     */
    public static function gbktoutf8($str) {
        return iconv("gbk", 'utf-8//IGNORE', $str);
    }

    /**
     * 字符编码转换 utf8 -> gbk
     */
    public static function utf8togbk($str) {
        return iconv("utf-8", 'gbk//IGNORE', $str);
    }

    /**
     * 数组编码转换 gbk2utf8
     *
     */
    public static function arr_gbktoutf8($arr){
        if(is_array($arr)){
            foreach($arr as $key=>$value){
                $arr[$key] = self::arr_gbktoutf8($value);
            }
        } else {
            if(!is_numeric($arr))
                $arr = self::gbktoutf8($arr);
        }
        return $arr;
    }

    /**
     * 数组编码转换 gbk2utf8
     *
     */
    public static function arr_utf8togbk($arr){
        if(is_array($arr)){
            foreach($arr as $key=>$value){
                $arr[$key] = self::arr_utf8togbk($value);
            }
        } else {
            if(!is_numeric($arr))
                $arr = self::utf8togbk($arr);
        }
        return $arr;
    }

    /***
     * 清除html
     *
     */
    public static function clearHTML($str) {
        $str = preg_replace("/<br[^>]*>\s*\r*\n*/is", "\n", $str);
        return str_replace('&nbsp;', ' ', strip_tags(htmlspecialchars_decode($str, ENT_NOQUOTES)));
    }

    /**
     * 剩余时间
     *
     */
    public static function gettime($time_s,$time_n){
        $strtime = '';
        $time = $time_n - $time_s;
        if($time >= 86400) {
            $strtime .= intval($time/86400) . '天';
            $time = $time % 86400;
        }
        if($time >= 3600) {
            $strtime .= intval($time/3600).'小时';
            $time = $time % 3600;
        } else {
            $strtime .= '';
        }
        return $strtime;
    }

    /**
     * 检测手机号
     *
     */
    public static function checkMobile($mobile) {
        if(preg_match("/^13[0-9]{9}$|15[0-9]{9}$|18[0-9]{9}$/",$mobile)){
            return true;
        }
        return false;
    }

    /**
     * 检测邮箱
     *
     */
    public static function checkMail($email) {
        if(filter_var($email, FILTER_VALIDATE_EMAIL)){
            return true;
        } else {
            return false;
        }
    }

    /**
     * 高进位转换
     *
     */
    public static function to36or62($num, $flag=1) {
        if($flag){
            $to = 36;
            $dict = '0123456789abcdefghijklmnopqrstuvwxyz';
        } else {
            $to = 62;
            $dict = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        }
        $ret = '';
        do {
            $ret = $dict[bcmod($num, $to)] . $ret;
            $num = bcdiv($num, $to);
        } while ($num > 0);
        return $ret;
    }

    /**
     * 所以数据转换为字符串字符串
     *
     */
    public static function arritemtostr($string) {
        if(is_array($string)) {
            $keys = array_keys($string);
            foreach($keys as $key) {
                $val = $string[$key];
                unset($string[$key]);
                $string[$key] = self::$arritemtostr($val);
            }
        } else {
            settype($string, "string");
        }
        return $string;
    }

    /**
     * 计算两点距离
     * 单位是米
     *
     */
    public static function getDistance($lat1, $lng1, $lat2, $lng2) {
        if(empty($lat1) || empty($lng1)){
            return 0;
        }
        $lat2 = floatval($lat2);
        $lng2 = floatval($lng2);
        $radLat1=deg2rad($lat1);
        $radLat2=deg2rad($lat2);
        $radLng1=deg2rad($lng1);
        $radLng2=deg2rad($lng2);
        $a=$radLat1-$radLat2;//两纬度之差,纬度<90
        $b=$radLng1-$radLng2;//两经度之差纬度<180
        $s=2*asin(sqrt(pow(sin($a/2),2)+cos($radLat1)*cos($radLat2)*pow(sin($b/2),2)))*6378137;
        $s=round($s*10000)/10000;
        return $s;
    }

    /**
     * 计算坐标范围
     * 单位是米
     *
     */
    public static function getRange($lat,$lon,$raidus) {
        //计算纬度
        $PI = 3.14159265;
        $latitude = $lat;
        $longitude = $lon;
        $degree = (24901*1609)/360.0;
        $raidusMile = $raidus;
        $dpmLat = 1/$degree;
        $radiusLat = $dpmLat * $raidusMile;
        $minLat = $latitude - $radiusLat;
        $maxLat = $latitude + $radiusLat;
        $mpdLng = $degree*cos($latitude * ($PI/180));
        $dpmLng = 1 / $mpdLng;
        $radiusLng = $dpmLng*$raidusMile;
        $minLng = $longitude - $radiusLng;
        $maxLng = $longitude + $radiusLng;
        $return = array();
        if($minLat > $maxLat) {
            $return[0] = $maxLat;
            $return[1] = $minLat;
        } else {
            $return[0] = $minLat;
            $return[1] = $maxLat;
        }
        if($minLng > $maxLng) {
            $return[2] = $maxLng;
            $return[3] = $minLng;
        } else {
            $return[2] = $minLng;
            $return[3] = $maxLng;
        }
        return $return;
    }

    /**
     * 数组分页
     * @param $pagesize int 每页条数
     * @param $page int 当前页
     * @param $array array 数组
     * @return array 分页结果
     *
     */
    public static function page_array($pagesize=10, $page=1, $array) {
        $page = intval($page)>0 ? $page : 1;
        $start = ($page-1) * $pagesize;
        $totals = count($array);
        $countpage = ceil($totals/$pagesize);
        $pagedata = array();
        $pagedata = array_slice($array, $start, $pagesize);
        unset($array);
        return $pagedata;
    }

}

?>
