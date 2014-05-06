<?php
/**
 *      [CodeJm!] Author CodeJm[codejm@163.com].
 *
 *      加密类
 *      $Id: passHash.php 2014-05-06 11:10:04 codejm $
 */

class PassHash {
	// blowfish
	private static $algo = '$2a';
	// cost parameter
	private static $cost = '$10';
	// mainly for internal use
	public static function unique_salt() {
		return substr(sha1(mt_rand()),0,22);
	}

	// this will be used to generate a hash
	public static function hash($password) {
		return md5($password);
	}

	// this will be used to compare a password against a hash
	public static function authenticate($password,$hash) {
		$new_hash = md5($password);
		return ($hash == $new_hash);
	}
}
