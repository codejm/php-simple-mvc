<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      Controller 父类
 *      $Id: baseController.php 2014-05-06 10:59:47 codejm $
 */

class BaseController {
    //
    public $db = null;
    public $cache = null;
    public $pageTitle = '';
    public $session_key = array('ErrorMessageStop', 'Messsage', 'SuccessMessage', 'ErrorMessage');

    /**
     * 模板输出
     * @param string $view 模板路径
     * @param array $data_array 数据模板
     *
     * @return template string
     */
    public function render($view, $data_array = array()) {
        // load Twig, the template engine
        // @see http://twig.sensiolabs.org
        $twig_loader = new Twig_Loader_Filesystem(PATH_VIEWS);
        $twig = new Twig_Environment($twig_loader);

        // ----------------------------------------------------
        // codejm add global valiable
        // ----------------------------------------------------
        $data_array['URL'] = URL;
        $data_array['R_URL'] = R_URL;
        $data_array['pageTitle'] = $this->pageTitle;
        $data_array['appName'] = APPNAME;

        foreach ($this->session_key as $key) {
            $value = help::getSession('session_key_'.$key);
            if(!empty($value)){
                $data_array[$key] = $value;
                help::setSession('session_key_'.$key, '');
            }
        }

        // url generate
        $twig->addFunction("url", new Twig_Function_Function("Route::generateUrl"));

        // render a view while passing the to-be-rendered data
        echo $twig->render($view . PATH_VIEW_FILE_TYPE, $data_array);
    }

    /**
     * redirect url跳转
     * @param string $url 要跳转的url
     * @param array $data_array 携带数据
     *
     */
    public function redirect($url, $data_array = array()) {
        if($data_array){
            foreach($this->session_key as $key){
                if(isset($data_array[$key]) && !empty($data_array[$key])){
                    help::setSession('session_key_'.$key, $data_array[$key]);
                }
            }
        }
        header('Location: '.$url);
        exit();
    }

    /**
     * 输出数组 转换成字符串
     *
     */
    public function output($data) {
        header('Content-type: application/json; charset=utf-8');
        if(empty($data))
            $data = array();
        else
            $data = help::arritemtostr($data);
        echo json_encode($data);
        exit;
    }
}
