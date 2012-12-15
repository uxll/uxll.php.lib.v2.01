<?php
class tbView implements ItbInitializeConfig{
	//���ܶ���
	/**
		ֻ�������ã����û�����þͲ�����
		config ��ʽ����
		{
			caption:___
			fieldsmanifest => array(
				field0  =>  array(
				type:type,
				extravalue:{
				
				}
			)
			fieldsdisplayname => array(
				field0:___
			)
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
		require_once(UXLL_TBHELPER_ROOT.'common/ui/view.php');	
	}
	public function init($tbname,$config,$engine){
		if(!$tbname)return false;
		if(!is_array($config))return  false;
		$this -> tbname = $tbname;
		$this -> config = $config;
		
		$ui = new tbhelperViewUI($engine);
		
		$v = $this -> getCaption();
		if($v)$ui -> setCaption($v);
		

		
		$v = $this -> getFieldsManifest();
		if($v)$ui -> setFieldsManifest($v);
		
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

	private function getExtraHTML(){
		if(array_key_exists('extrahtml',$this -> config)){
			return $this -> config['extrahtml'];	
		}
		return null;
	}
}