<?php
class tbDisplay implements ItbInitializeConfig{
	//功能定义
	/**
		只处理配置，如果没有配置就不处理
		config 格式定义
		{//display
			Caption
			url:{addurl,editurl,updateurl...}
			FieldsManifest => array(
				Field0 =>表的字段内容显示 这个显示最终在UI和模板之间进行
				Field1 =>表的字段内容显示
			)
			ExtraFields => 表的字段内容显示
			FieldsDisplayName => array(
				Field0 => displayname
			)
			//AdditionalToolBar
			ExtraHTML
		
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
		require_once(UXLL_TBHELPER_ROOT.'common/ui/display.php');	
	}
	public function init($tbname,$config,$engine){
		if(!$tbname)return false;
		if(!is_array($config))return  false;
		$this -> tbname = $tbname;
		$this -> config = $config;
		
		$ui = new tbhelperDisplayUI($engine);
		
		$v = $this -> getCaption();
		if($v)$ui -> setCaption($v);
		
		$v = $this -> geturl();
		if($v)$ui -> seturl($v);
		
		$v = $this -> getFieldsManifest();
		if($v)$ui -> setFieldsManifest($v);
		
		$v = $this -> getExtraFields();
		if($v)$ui -> setExtraFields($v);
		
		$v = $this -> getFieldsDisplayName();
		if($v)$ui -> setFieldsDisplayName($v);
		
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
	private function geturl(){
		if(array_key_exists('url',$this -> config)){
			return $this -> config['url'];	
		}
		return null;
	}
	private function getFieldsManifest(){
		if(array_key_exists('fieldsmanifest',$this -> config)){
			return $this -> config['fieldsmanifest'];	
		}
		return null;
	}
	private function getExtraFields(){
		if(array_key_exists('extrafields',$this -> config)){
			return $this -> config['extrafields'];	
		}
		return null;
	}
	private function getFieldsDisplayName(){
		if(array_key_exists('fieldsdisplayname',$this -> config)){
			return $this -> config['fieldsdisplayname'];	
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