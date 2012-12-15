<?php
class tbhelperAppendUI{
	private $datawrap = null;
	private $patterns = "";
	private $tbname = null;
	private $engine;
	private $displayname;
	

	public function __construct($tbhelperBase){
		$this -> engine = $tbhelperBase;
	}
	public function setTbname($v){
		$this -> tbname = $v;
	}
	public function setDatawrap($v){
		$this -> datawrap = $v;
	}
	public function getDatawrap(){
		return $this -> datawrap;
	}
	public function setPatterns($v){
		$this -> patterns = $v;
	}
	public function getPatterns(){
		return $this -> patterns;
	}	
	public function setFieldsDisplayName($v){
		$this -> displayname = $v;
	}
	public function getFieldsDisplayName(){
		return $this -> displayname;
	}
	
}