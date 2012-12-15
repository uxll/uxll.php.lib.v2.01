<?php
class tbhelperViewUI{
	private $caption = null;

	private $fieldsmanifest = null;
	private $row;
	private $fieldsdisplayname = null;
	private $extrahtml = null;
	private $tbname = null;

	private $allfields = null;


	private $engine;
	
	private $tbdescription;
	
	private $viewdescripton;

	
	public function __construct($tbhelperBase){
		$this -> engine = $tbhelperBase;
		$this -> tbdescription = $this -> engine -> getTbDescripton();

	}
	
	public function getLastUrl() {
		return R('HTTP') -> frontUrl();
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



	public function setViewDescription($v) {
		$this -> viewdescripton = $v;
	}

	public function setAllFields($v) {
		$this -> allfields = $v;
	}
	
	public function getViewDescription() {
		return $this -> viewdescripton;
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
	public function html($field,$row,$pk,$action="view") {
		$type = strtolower($this -> engine -> getFieldType($field));
		$fieldsmanifest = $this -> getFieldsManifest();
		$default = $row[$field];
		$extravalue = array();
		if(is_array($fieldsmanifest) && array_key_exists($field,$fieldsmanifest)){
			if(is_string($fieldsmanifest[$field])){
				return $this -> engine -> tbManifest -> execute($fieldsmanifest[$field],$row,$field);	
			}
			if(array_key_exists("type",$fieldsmanifest[$field])){
				$type = $fieldsmanifest[$field]["type"];
			}
			if(array_key_exists("extravalue",$fieldsmanifest[$field])){
				$extravalue = $fieldsmanifest[$field]["extravalue"];
			}
		}
		if(!$extravalue){
			$extravalue = $this -> getFieldsExtraData($field,$type,$pk,$default);
		}else{
			$extravalue = $this -> getFieldsExtraData($field,$type,$pk,$default,$extravalue);
		}
//echo $type;
		return $this -> getHTML($action,$type,$extravalue);
	}
	public function setRows($row) {
		$this -> row = $row;
	}
	public function getRows() {
		return $this -> row;
	}
	public function getPrimaryKey(){
		return $this -> engine -> getPrimaryKey();	
	}
//--------------------------------------------------------------------------------------------------------------------
	private function getEnumByDescription($subject) {
		if(preg_match("/^[a-z]+\(('[\w-]+'(,'[\w-]+')*)\)$/", $subject, $matches)){
			return explode("','",substr($matches[1], 1,-1));
		}
		return array();
	}
	private function getFieldsExtraData($field,$type,$pk,$default=null,$extravalue=null) {
		if(is_null($extravalue)){
			$extraData = array();
		}else{
			$extraData = $extravalue;
		}
		$type = $this -> engine -> getType($type);

		switch($type){
			case "textarea":
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
			case "datetime":
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? date('Y-m-d H:i:s',time() + 8*60*60) : $default;
				break;
			case "date":
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? date('Y-m-d',time() + 8*60*60) : $default;
				break;
			
			case "enum":
			case "set":
				$fieldsmanifest = $this -> getFieldsManifest();
				$description =$this -> getViewDescription();//
				$description_edit = $this -> getViewDescription();//
				
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
					$enum = $this -> getEnumByDescription(
						$description_edit
						[$field]["Type"]
					);
				}
				
				if(is_null($value)){
					$value = $enum;
				}
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				if(!array_key_exists('enum',$extraData))$extraData['enum'] = $enum;
				if(!array_key_exists('value',$extraData))$extraData['value'] = $value;
				
				break;
			case "varbinary":
				if(!array_key_exists('width',$extraData))$extraData['width'] = 300;
				if(!array_key_exists('height',$extraData))$extraData['height'] = 150;
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
			case "text":
				if(!array_key_exists('defaultvalue',$extraData))$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
		}
		return $extraData;
	}	
}