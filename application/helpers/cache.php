<?php

/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      缓存制造类，缓存基类
 *      $Id: Cache.php 2014-04-09 09:05:46 codejm $
 */

class Cache {
	public $cache;

	const TYPE_FILE = 0;
	const TYPE_MEMCACHE = 1;
	const TYPE_APC = 2;
	const TYPE_XCACHE = 3;
	const TYPE_EAC = 4;
	const TYPE_DBCACHE = 5;
	const TYPE_WINCACHE = 6;
	const TYPE_SAEMEMCACHE = 7;
	static $backends = array('FileCache', 'Memcache', 'ApcCache', 'XCache', 'EAcceleratorCache', 'DBCache', 'WinCache', 'SaeMemcache');

	/**
	 * 获取缓存对象
	 * @param $scheme
	 * @return cache object
	 */
	static function create($config) {
        if(empty(self::$backends[$config['type']])) {
            return Error::info('Cache Error',"cache backend: {$config['type']} no support");
        }
		$backend = "Cache_".self::$backends[$config['type']];
		return new $backend($config);
	}
}
