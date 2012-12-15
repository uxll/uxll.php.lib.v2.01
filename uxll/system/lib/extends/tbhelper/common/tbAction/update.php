<?php
class tbUpdate implements ItbInitializeConfig{
	//功能定义
	/**
		只处理配置，如果没有配置就不处理
		config 格式定义
		{
			datawrap:{
				field0:"classname::method",
				field1:"classname::method",
				field2:"classname::method",
			},
			patterns:{
				field0:"/regexp/"
				field1:"/regexp/"
			},
			fieldsdisplayname:{
				field0:___
			}

		}

	*/
	private $error;
	private $config;
	private $tbname;

	
	public function getLastError(){
		return $this ->  error;	
	}
	public function __construct(){
		require_once(UXLL_TBHELPER_ROOT.'common/ui/update.php');	
	}
	public function init($tbname,$config,$engine){
		if(!$tbname)return false;
		if(!is_array($config))return  false;
		$this -> tbname = $tbname;
		$this -> config = $config;
		
		$ui = new tbhelperUpdateUI($engine);
		
		$v = $this -> getDatawrap();
		if($v)$ui -> setDatawrap($v);
		
		$v = $this -> getPatterns();
		if($v)$ui -> setPatterns($v);
		
		$v = $this -> getFieldsDisplayName();
		if($v)$ui -> setFieldsDisplayName($v);
		
		$ui -> setTbname($this -> tbname);
		return $ui;
	}
//---------------------------------------------------------------------------------

	private function getDatawrap(){
		if(array_key_exists('datawrap',$this -> config)){
			return $this -> config['datawrap'];	
		}
		return null;
	}
	
	private function getPatterns(){
		if(array_key_exists('patterns',$this -> config)){
			return $this -> config['patterns'];	
		}
		return null;
	}
	
	private function getFieldsDisplayName(){
		if(array_key_exists('fieldsdisplayname',$this -> config)){
			return $this -> config['fieldsdisplayname'];	
		}
		return null;
	}
}