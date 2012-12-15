<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Session.php
 */
class CSession{
	private static $_instance = null;
	private function __construct(){}
	static function getInstance(){
		if (null === self::$_instance){
			self::$_instance = new self();
			session_start();
		}
		return self::$_instance;
	}
	public function id($id = null){
		return isset($id) ? session_id($id) : session_id();
	}
	public function name($name = null){
		return isset($name) ? session_name($name) : session_name();
	}
	public function setExpire($gcMaxLifetime = null){
		$return = ini_get('session.gc_maxlifetime');
		if (isset($gcMaxLifetime) && is_int($gcMaxLifetime) && $gcMaxLifetime >= 1) {
			ini_set('session.gc_maxlifetime', $gcMaxLifetime);
		}
		return $return;
	}
	public function get($name){
		if(isset($_SESSION[$name])) {
			return $_SESSION[$name];
		}else {
			return null;
		}
	}
	public function set($name, $value){
		if (null === $value) {
			unset($_SESSION[$name]);
		} else {
			$_SESSION[$name] = $value;
		}
		return $value;
	}
	public function clear(){
		$_SESSION = array();
	}
	public function destroy(){
		unset($_SESSION);
		session_destroy();
	}
}