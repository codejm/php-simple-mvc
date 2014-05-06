<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      分析搜索引擎到来的关键字
 *      $Id: searchEngine.php 2014-04-03 15:23:45 codejm $
 */

class SearchEngine {
	public static $banUrl = array('qq.com','localhost'); //不解析的referer 有利于加快程序处理速度

	public static $parseHost = array('baidu','google','360','soso');  //能解析的referer

	/**
	 * 主方法
	 * @return boolean || array
	 */
	public static function keyword($referer) {
		if(!isset($_SERVER['HTTP_REFERER']))
			return false;
		$referer = trim($_SERVER['HTTP_REFERER']);
		$refererArr = parse_url($referer);

		//判断refer是否来至不需要分析的地址。
		if(self::inBanUrl($refererArr['host']))
			return false;

		$hasParseFun = false;
		foreach(self::$parseHost as $host) {
			if(strpos ($refererArr['host'],$host) !== false) {
				$hasParseFun = true;
				break;
			}
		}

		if(!$hasParseFun)
			return false;

		$queryVars = array();
		parse_str($refererArr['query'], $queryVars);
		//调用每个搜索引擎的单独处理方法
		$method = 'parse'.ucfirst($host);
		return self::$method($queryVars);
	}

	public static function inBanUrl($referer) {
		foreach(self::$banUrl as $url) {
			if(strpos($url, $referer) !== false)
				return true;
		}
		return false;
	}

    // 百度
	public static function parseBaidu($params) {
		$searchEngine = '';
		if(isset($params['kw'])) {
			$searchEngine = $params['kw'];
		} else if(isset ($params['wd'])) {
			$searchEngine = $params['wd'];
		} else if(isset ($params['word'])) {
			$searchEngine = $params['word'];
		}

		return '百度:'.(isset($params['ie']) && (strtolower($params['ie']) == 'utf-8') ? $searchEngine : iconv('gbk', 'utf-8', $searchEngine));
	}

    // 谷歌
	public static function parseGoogle($params) {
		$searchEngine = '';
		if(isset($params['q'])) {
			$searchEngine = $params['q'];
		}
		return '谷歌:'.(isset($params['ie']) && ($params['ie'] == "GB") ? iconv('gbk', 'utf-8', $searchEngine) : $params['q']);
	}

    // 360
	public static function parse360($params) {
		$searchEngine = '';
		if(isset($params['q'])) {
			$searchEngine = $params['q'];
		}
		return '360:'.$searchEngine;
	}

    // 搜搜
	public static function parseSoso($params) {
		$searchEngine = '';
		if(isset($params['w'])) {
			$searchEngine = $params['w'];
		}
		return '搜搜:'.iconv('gbk', 'utf-8', $searchEngine);
	}
}

?>
