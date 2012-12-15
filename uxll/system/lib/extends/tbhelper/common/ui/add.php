<?php
class tbhelperAddUI{
	private $caption = null;
	private $url = "";
	private $fieldsmanifest = null;

	private $fieldsdisplayname = null;
	private $extrahtml = null;
	private $tbname = null;
	private $submitbtntext = null;
	private $method = null;
	private $allfields = null;

//	private $privmask = null;
	private $engine;
	
	private $tbdescription;
	
	private $adddescripton;
	private $appenddescripton;
	
	public function __construct($tbhelperBase){
		$this -> engine = $tbhelperBase;
		$this -> tbdescription = $this -> engine -> getTbDescripton();

	}
	public function setTbname($v){
		$this -> tbname = $v;
	}
	public function setCaption($v){
		$this -> caption = $v;
	}
	public function getCaption(){
		return $this -> caption;
	}
	public function setAppendUrl($v){
		$this -> url = $v;
	}
	public function getAppendUrl(){
		return $this -> url;
	}	


	public function setFieldsManifest($v){
		$this -> fieldsmanifest = $v;
	}
	public function getFieldsManifest(){
		return $this -> fieldsmanifest;
	}

	public function setFieldsDisplayName($v){
		$this -> fieldsdisplayname = $v;
	}
	public function getFieldsDisplayName(){
		return $this -> fieldsdisplayname;
	}
	public function setExtraHTML($v){
		$this -> extrahtml = $v;
	}
	public function getExtraHTML(){
		return $this -> extrahtml;
	}
	public function getSubmitBtnText() {
		return $this -> submitbtntext;
	}
	public function setSubmitBtnText($v) {
		$this -> submitbtntext = $v;
	}
	public function getMethod() {
		return $this -> method;
	}
	public function setMethod($v) {
		$this -> method = is_string($v) && strtolower($v) === 'get' ? 'get' : 'post';
	}

	
	public function allowedAjax(){
		foreach($this -> getAppendDescription() as $val){
			if('varbinary' === strtolower($this -> engine -> getType(
				preg_replace("/[^a-zA-Z]/","",$val["Type"])
			)))return false;
		}
		return true;
	}

	public function setAddDescription($v) {
		$this -> adddescripton = $v;
	}
	public function setAppendDescription($v) {
		$this -> appenddescripton = $v;
	}
	public function setAllFields($v) {
		$this -> allfields = $v;
	}
	
	public function getAddDescription() {
		return $this -> adddescripton;
	}
	public function getAppendDescription() {
		return $this -> appenddescripton;
	}
	public function getAllFields() {
		return $this -> allfields;
	}
	
	public function getHTML($action,$type,$extraData) {
		return $this -> engine -> getHTML($action,$type,$extraData);
	}
	public function getFieldFlag($field) {
		return $this -> engine -> getFieldFlag($field) ;
	}
	public function html($field) {
		$type = strtolower($this -> engine -> getFieldType($field));
		$fieldsmanifest = $this -> getFieldsManifest();
#		echo P($fieldsmanifest);
		$extravalue = array();
		if(is_array($fieldsmanifest) && array_key_exists($field,$fieldsmanifest)){

			if(array_key_exists("type",$fieldsmanifest[$field])){
				$type = $fieldsmanifest[$field]["type"];
			}
			if(array_key_exists("extravalue",$fieldsmanifest[$field])){
				$extravalue = $fieldsmanifest[$field]["extravalue"];
			}
		}
#echo P($extravalue);		
		if(!$extravalue){
			$extravalue = $this -> getFieldsExtraData($field,$type);
		}else{
			$extravalue = $this -> getFieldsExtraData($field,$type,null,$extravalue);
		}

		return $this -> getHTML("add",$type,$extravalue);
	}
	
//--------------------------------------------------------------------------------------------------------------------
	private function getEnumByDescription($subject) {
		if(preg_match("/^[a-z]+\(('[\w-]+'(,'[\w-]+')*)\)$/", $subject, $matches)){
			return explode("','",substr($matches[1], 1,-1));
		}
		return array();
	}
	private function getFieldsExtraData($field,$type,$default=null,$extravalue=null) {
		if(is_null($extravalue)){
			$extraData = array();
		}else{
			$extraData = $extravalue;
		}
		$type = $this -> engine -> getType($type);
		switch($type){
			case "textarea":
				if(!array_key_exists('cols',$extraData))$extraData['cols'] = 40;
				if(!array_key_exists('rows',$extraData))$extraData['rows'] = 5;
				if(!array_key_exists('name',$extraData))$extraData['name'] = $field;
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
			case "datetime":
				if(!array_key_exists('name',$extraData))$extraData['name'] = $field;
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? date('Y-m-d H:i:s',time() + 8*60*60) : $default;
				break;
			case "date":
				if(!array_key_exists('name',$extraData))$extraData['name'] = $field;
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? date('Y-m-d',time() + 8*60*60) : $default;
				break;
			
			case "enum":
			case "set":
				$fieldsmanifest = $this -> getFieldsManifest();
				$description = $this -> getAppendDescription();
				$value = null;
				$enum = null;
				if(is_array($fieldsmanifest) && array_key_exists($field,$fieldsmanifest)){
					if(array_key_exists('value',$fieldsmanifest[$field])){
						$value = $fieldsmanifest[$field]['value'];
					}
					if(array_key_exists('enum',$fieldsmanifest[$field])){
						$enum = $fieldsmanifest[$field]['enum'];
					}
				}
				if(is_null($enum)){
					$enum = $this -> getEnumByDescription($description[$field]["Type"]);
				}
				
				
#echo P($value);				
				
				if(is_null($value)){
					$value = $enum;
				}
				if(!array_key_exists('name',$extraData))$extraData['name'] = $field;
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				if(!array_key_exists('enum',$extraData))$extraData['enum'] = $enum;
				if(!array_key_exists('value',$extraData))$extraData['value'] = $value;
				
				break;
			case "varbinary":
				if(!array_key_exists('name',$extraData))$extraData['name'] = $field;
				break;
			case "text":
				$len = $this -> engine -> getFieldLen($field);
				if(!array_key_exists('name',$extraData))$extraData['name'] = $field;
				if(!array_key_exists('len',$extraData))$extraData['len'] = $len;
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
		}
		return $extraData;
	}	
}