<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: IdentityToken.php
 */
class CIdentityToken implements arrayaccess{
	private $info = array();
	private $ip = '';
	private $role = 0;
	public function __construct(){
		$this -> ip = R("HTTP") -> getClientIp();
	}	

	public function offsetSet($offset, $value){
		$r = & $this -> _key($offset);
		if(is_array($r)){
			 $r[$offset] = $value;
			 return true;
		}
		$r = $value;
	}
	public function offsetExists($offset) {
		$r = $this -> _key($offset);
		if(is_array($r)){
			if(array_key_exists($offset,$r))return isset($r[$offset]);
			return false;
		}
		return isset($r);
	}
	public function offsetUnset($offset) {
		$r = & $this -> _key($offset);
		if(is_array($r)){
			unset($r[$offset]);
		}
		unset($r);
	}
	public function offsetGet($offset) {
		$value = $this -> _key($offset);
		if(is_array($value)){
			if(array_key_exists($offset,$value))return $value[$offset];
			return null;
		}
		return $value;
	}
//---------------------------------------------------------------------		
	private function & _key($offset){
		$_role = $this -> _role($offset);
		$_ip = $this -> _ip($offset);
		if($_role)return $this -> role;
		if($_ip)return $this -> ip;
		return $this -> info;
	}

	private function _role($offset){
		return (substr($offset,0,1) === '@');
	}	
	private function _ip($offset){
		return (substr($offset,0,1) === '#');
	}		
}