<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Message.php
 */
class CMessage implements arrayaccess{
	public $control;
	public $action;	
	private $arguments;
	private $data;//post data
	private $turnOnFlag = 'off';
	private $is_valid = false;
	public function __construct($httpRequest=array(),$data=null){
		$this -> build($httpRequest,$data);
	}
	public function __set($k,$v){
		return false;	
	}
	public function build($httpRequest=array(),$data=null){
		$i = 0;
		if(!is_array($httpRequest)){
			new CError(__CLASS__.' build failed.');
			return false;
		}
		if(array_key_exists('control',$httpRequest)){
			$i++;
			$this -> control = $httpRequest['control'];	
		}
		if(array_key_exists('action',$httpRequest)){
			$i++;
			$this -> action = $httpRequest['action'];	
		}
		if(array_key_exists('arguments',$httpRequest)){
			$i++;
			$this -> arguments = $httpRequest['arguments'];	
		}
		$this -> data = is_null($data) ? CHttpRequest::getInstance() -> getPost() : $data;
		$this -> _unsetUserData();
		$this -> is_valid = $i === 3;
	}
	public function isValid(){
		return $this -> is_valid;
	}
	public function get(){
		$data = array();
		$data['control'] = $this -> getControl();	
		$data['action'] = $this -> getAction();	
		$data['arguments'] = $this -> getArguments();
		$data['data'] = $this -> getData();
		return $data;			
	}
	public function getControl(){
		return $this -> control;	
	}
	public function getAction(){
		return $this -> action;	
	}
	public function getArguments(){
		return $this -> arguments;	
	}
	public function getData(){
		return $this -> data;	
	}
	public function turnOn(){
		if($this -> turnOnFlag === 'off'){
			$this -> turnOnFlag = 'on';
			$this -> arguments = $this -> _turnOn($this -> arguments);
			$this -> data = $this -> _turnOn($this -> data);
		}
		
	}
	public function turnOff(){
		if($this -> turnOnFlag === 'on'){
			$this -> turnOnFlag = 'off';
			$this -> arguments = $this -> _turnOff($this -> arguments);
			$this -> data = $this -> _turnOff($this -> data);
		}
	}	
	public function is_default_action($a=null){
		return (is_null($a) ? $this -> action : $a) === $GLOBALS['UXLL_CONFIG']['request']['DEFAULT_ACTION_NAME'];
	}
	public function is_default_control($a=null){
		return (is_null($a) ? $this -> control : $a) ===  $GLOBALS['UXLL_CONFIG']['request']['DEFAULT_CONTROLLER_NAME'];
	}
	
	public function offsetSet($offset, $value){
		$r = & $this -> _key($offset);
		if($r === false)return false;
		$offset = $this -> _offset($offset);
		$r[$offset] = $value;
		return true;
	}
	public function offsetExists($offset) {
		$r = $this -> _key($offset);
		$offset = $this -> _offset($offset);
		return isset($r[$offset]);
	}
	public function offsetUnset($offset) {
		$r = & $this -> _key($offset);
		$offset = $this -> _offset($offset);
		unset($r[$offset]);
	}
	public function offsetGet($offset) {
		$value = $this -> _key($offset);
		if(!is_array($value)){
			return false;	
		}
		$offset = $this -> _offset($offset);
		if(array_key_exists($offset,$value)){
			$value = $value[$offset];
		}else{
			$value = null;	
		}
		return $value;
	}
//---------------------------------------------------------------------	
	private function _unsetUserData(){
		unset($_POST);
		unset($_GET);
	}	
	private function & _key($offset){
		$x = false;
		$_args = $this -> _args($offset);
		$offset = $this -> _offset($offset);
		if($_args)return $this -> arguments;
		return $this -> data;
	}
	private function _get_user_post_data($value){
		$value = is_array($value) ? array_map(array($this,__FUNCTION__), $value) : addslashes($value);
		return $value;
	}
	private function _offset($offset){
		if($this -> _args($offset)){
			$offset = substr($offset,1);
		}
		return $offset;		
	}
	private function _args($offset){
		return (substr($offset,0,1) === ':');
	}
	private function _turnOff($value){
		$value = is_array($value) ? array_map(array($this,__FUNCTION__), $value) : stripslashes($value);
		return $value;
	}
	private function _turnOn($value){
		$value = is_array($value) ? array_map(array($this,__FUNCTION__), $value) : addslashes($value);
		return $value;
	}		
}