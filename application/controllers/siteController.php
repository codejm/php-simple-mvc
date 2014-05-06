<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      site
 *      $Id: siteController.php 2014-04-08 14:26:17 codejm $
 */

class SiteController extends BaseController {

    public function index() {
        $this->render('site/index', array('name'=>'codejm'));
    }

}

?>
