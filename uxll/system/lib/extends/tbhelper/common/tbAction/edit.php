<?php
class tbEdit implements ItbInitializeConfig{
	//功能定义
	/**
		只处理配置，如果没有配置就不处理
		config 格式定义
		{
			caption:___
			updateurl:___
			method:___
			fieldsmanifest => array(
				field0  =>  array(
				type:type,
				extravalue:{
				
				}
			)
			fieldsdisplayname => array(
				field0:___
			)
			submitbtntext:___
			extrahtml:___

		}

	*/
	private $error;

	private $userFunc;
	private $config;
	private $tbname;

	
	public function getLastError(){
		return $this ->  error;	
	}
	public function __construct(){
		require_once(UXLL_TBHELPER_ROOT.'common/ui/edit.php');	
	}
	public function init($tbname,$config,$engine){
		if(!$tbname)return false;
		if(!is_array($config))return  false;
		$this -> tbname = $tbname;
		$this -> config = $config;
		
		$ui = new tbhelperEditUI($engine);
		
		$v = $this -> getCaption();
		if($v)$ui -> setCaption($v);
		
		$v = $this -> getUpdateUrl();
		if($v)$ui -> setUpdateUrl($v);
		
		$v = $this -> getMethod();
		if($v)$ui -> setMethod($v);
		
		$v = $this -> getFieldsManifest();
		if($v)$ui -> setFieldsManifest($v);
		
		$v = $this -> getFieldsDisplayName();
		if($v)$ui -> setFieldsDisplayName($v);
		
		$v = $this -> getSubmitBtnText();
		if($v)$ui -> setSubmitBtnText($v);
		
		
		$v = $this -> getExtraHTML();
		if($v)$ui -> setExtraHTML($v);
		
		$ui -> setTbname($this -> tbname);
		return $ui;
	}
//---------------------------------------------------------------------------------

	private function getCaption(){
		if(array_key_exists('caption',$this -> config)){
			return $this -> config['caption'];	
		}
		return null;
	}
	
	private function getUpdateUrl(){
		if(array_key_exists('updateurl',$this -> config)){
			return $this -> config['updateurl'];	
		}
		return null;
	}
	
	private function getMethod() {
		if(array_key_exists('method',$this -> config)) {
			return $this -> config['method'];	
		}
		return null;
	}
	private function getFieldsManifest(){
		if(array_key_exists('fieldsmanifest',$this -> config)){
			return $this -> config['fieldsmanifest'];	
		}
		return null;
	}

	private function getFieldsDisplayName(){
		if(array_key_exists('fieldsdisplayname',$this -> config)){
			return $this -> config['fieldsdisplayname'];	
		}
		return null;
	}

	private function getSubmitBtnText(){
		if(array_key_exists('submitbtntext',$this -> config)){
			return $this -> config['submitbtntext'];	
		}
		return null;
	}
	private function getExtraHTML(){
		if(array_key_exists('extrahtml',$this -> config)){
			return $this -> config['extrahtml'];	
		}
		return null;
	}
}