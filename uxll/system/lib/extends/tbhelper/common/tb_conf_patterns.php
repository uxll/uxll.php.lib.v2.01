<?php
class tbConfigPattern extends tbhelper{
	private $error;
	public function getLastError(){
		return $this ->  error;	
	}
	public function check($action,$fieldname,$check_value,$config_patterns=null){
		if(!is_array($config_patterns))return true;
		if(!array_key_exists($action,$config_patterns))return true;
		if(!is_array($config_patterns[$action]))return true;
		if(!array_key_exists($fieldname,$config_patterns[$action]))return true;
		$r = preg_match($config_patterns[$action][$fieldname],$check_value);
		if($r === false){
			$this -> error = $this -> L("Matching faild",array('regexp' => $config_patterns[$action][$fieldname], 'value' => $check_value));	
			return false;
		}
		return true;
	}

}