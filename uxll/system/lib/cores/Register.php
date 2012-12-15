<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Register.php
 */
class CRegister{
	private static $_instance = null;
	private $key = 'UXLL_REGISTER';
	private function __construct(){
		$GLOBALS[$this -> key] = array();
	}
	static public function getInstance(){
		if (null === self::$_instance){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function save($key,$val){
		$GLOBALS[$this -> key][$key] = $val;
		return $this;
	}
	public function read($key){
		return isset($GLOBALS[$this -> key][$key]) ? $GLOBALS[$this -> key][$key] : null;
	}	
	public function remove($key){
		unset($GLOBALS[$this -> key][$key]);	
		return $this;
	}
}