<?php
class tbDescription extends CModel{
	private $isValid = false;
	private $error = '';
	private $tbname;

	private $description;
	public function __construct($tbname){
		parent::__construct();
		$this -> tbname = $tbname;
		$this -> getTbDescription();

	}
	public function getCaption(){
		$caption = $this -> getTabelComent($this -> tbname);
		if($caption){
			return $caption;
		}else{
			return $this -> tbname;
		}
	}
	public function getFields($field=null){
		return $this -> _getFields("Field",$field);
	}
	public function getFieldName($field=null){
		$rv = $this -> _getFields("Comment",$field);
		if(!is_null($field)){
			if(!$rv)return $field;
			return $rv;	
		}
		foreach($rv as $k => $v){
			if(!($v)){
				$rv[$k] = $k;	
			}	
		}
		return $rv;
		
	}
	public function getFieldType($field=null){
		
		$type = $this -> _getFields("Type",$field);
		$type = $this -> _split_typelen($type);
		return $type['type'];
	}
	public function getFieldFlag($field=null){
		return $this -> _getFields("Null",$field);
	}
	public function getFieldLen($field=null){
		$type = $this -> _getFields("Type",$field);
		$type = $this -> _split_typelen($type);
		return $type['len'];
	}
	public function getTbDescription(){
		$this -> description = $this -> getTableDecription($this ->  tbname);
		return $this -> description;
	}	
	public function getPrimaryKey() {
		return parent::getPrimaryKey($this -> tbname);
	}	
//---------------------------------------------------------------


	private function _getFields($name="Field",$field=null){
		$k = array();

		foreach($this -> description as $val){
			$k[$val["Field"]] = $name==="Null" ? $val[$name] === "YES":$val[$name];
		}
		if(!is_null($field) && array_key_exists($field,$k)){
			return $k[$field];	
		}
		return $name==="Field" ? array_values($k) : $k;
	}
	private function _split_typelen($t){
		if(preg_match("/^[a-z]+$/",$t)){
			return array(
				'type' => $t,
				'len' => null
			);
		}else if(preg_match("/^([a-z]+)\(([^\)]+)\)$/",$t,$matches)){
			return array(
				'type' => $matches[1],
				'len' => str_replace("'","",$matches[2])
			);
		}else{
			return array(
				'type' => null,
				'len' => null
			);
		}
	}	
}