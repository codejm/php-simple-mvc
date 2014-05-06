<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      路由类
 *      $Id: route.php 2014-05-03 13:03:24 codejm $
 */

class Route {
    public $controller = '';
    public $action = '';
    public $file = '';
    public $dir = '';

    /**
     * framework autoload
     *
     */
    public function autoload($class) {
        // module controller action
        // controller action
        // public
        // core
        $class = lcfirst($class);

        if(strpos($class, 'base') !== FALSE) {
            if(file_exists($file = $this->dir.$class.'.php')) {
                require $file;
                return true;
            }
        }
        if(strtolower($class) == 'controller' || strtolower($class) == 'models'){
            if(file_exists($file = CORE_PATH.$class.'.php')) {
                require $file;
                return true;
            }
        }
        if(strpos($class, 'Controller') !== FALSE) {
            $temp_dir = dirname($this->dir);
            if(file_exists($file = $temp_dir.'/controllers/'.$class.'.php')) {
                require $file;
                return true;
            }
        }
        if(strpos($class, 'Model') !== FALSE) {
            $temp_dir = dirname($this->dir);
            if (file_exists($file = $temp_dir.'/models/'.$class.'.php')) {
                require $file;
                return true;
            }
        }
        if(strpos($class, '_') !== FALSE) {
            $fileArr = explode("_", strtolower($class));
            if (count($fileArr) > 1) {
                $file = CORE_PATH.$fileArr[0].'/'.$fileArr[1].'.php';
                if (file_exists($file)) {
                    require $file;
                    return true;
                }
            }
        } else if(file_exists($file = HELPERS_PATH.$class.".php")) {
            require $file;
            return true;
        }
}

/**
 * URL 路由解析 core
 *
 */
public function parseURL() {
    // module controller action
    // get
    $path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

    // split URL
    $path = trim($path, '/');
    if(empty($path))
        $path = array();
    else {
        $path = explode('/', $path);
        if(CHILDDIR) {
            $path = array_slice($path, CHILDDIR);
        }
    }

    // default
    $dir = false;
    if(isset($path[0])) {
        if(is_dir(WEB_ROOT.$path[0])) {
            $dir = true;
            if(!isset($path[1])) $path[1] = DEFAULT_CONTROLLER;
            $this->controller = $path[0].'_'.$path[1].'Controller';
            $path = array_slice($path, 1);
        } else {
            $this->controller = $path[0].'Controller';
        }
        if(!isset($path[1])) $path[1] = DEFAULT_ACTION;
        $this->action = $path[1];
        $params = array_slice($path, 2);

        // 参数处理
        $params_len = count($params);
        for($i=0; $i<$params_len; $i=$i+2){
            if(isset($params[$i+1])){
                $_GET[$params[$i]] = $params[$i+1];
                $_REQUEST[$params[$i]] = $params[$i+1];
            }
        }
    } else {
        $this->controller = DEFAULT_CONTROLLER.'Controller';
        $this->action = DEFAULT_ACTION;
    }

    // 子目录处理
    if($dir) {
        $this->file = str_replace('_', '/controllers/', $this->controller);
        $this->file = WEB_ROOT.dirname($this->file).'/'.$this->controller.'.php';
    } else {
        $this->file = APP_PATH.'controllers/'.$this->controller.'.php';
    }
    $this->dir = dirname(dirname($this->file)).'/components/';

    // 实例化Controller
    if(file_exists($this->file)) {
        $object = new $this->controller();
        // 调用Action
        if (method_exists($object, $this->action)) {
            $object->{$this->action}();
            exit;
        }
    }
    header("HTTP/1.1 404 Not Found");
    header("Status: 404 Not Found");
        exit;
    }

    /**
     * getPathInfo 获取当前完整URL
     *
     * @return string $url
     */
    public function getPathInfo(){
        $_requestUri = '';
        if(isset($_SERVER['HTTP_X_REWRITE_URL']))
            $_requestUri=$_SERVER['HTTP_X_REWRITE_URL'];
        elseif(isset($_SERVER['REQUEST_URI'])) {
            $_requestUri=$_SERVER['REQUEST_URI'];
            if(!empty($_SERVER['HTTP_HOST'])) {
                if(strpos($_requestUri,$_SERVER['HTTP_HOST'])!==false)
                    $_requestUri=preg_replace('/^\w+:\/\/[^\/]+/','',$_requestUri);
            }
            else
                $_requestUri=preg_replace('/^(http|https):\/\/[^\/]+/i','',$_requestUri);
        } elseif(isset($_SERVER['ORIG_PATH_INFO'])) {
            // IIS 5.0 CGI
            $_requestUri=$_SERVER['ORIG_PATH_INFO'];
            if(!empty($_SERVER['QUERY_STRING']))
                $_requestUri.='?'.$_SERVER['QUERY_STRING'];
        }
        return $_requestUri;
    }

    /**
     * url 生成
     * @param string $route 路由
     * @param array $param 参数数组
     *
     * @return string $url
     */
    public static function generateUrl($route, $params = array()) {
        $url = URL.$route;
        $url = rtrim($url, '/');
        foreach($params as $key=>$value){
            $url .= '/'.$key.'/'.$value;
        }
        return $url;
    }

}

?>
