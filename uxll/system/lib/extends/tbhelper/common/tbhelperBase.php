<?php
class tbhelperBase{
	private $error = 0;
	private $isValid = false;
	
	private $tbDescription;
	public function isValid(){
		return $this -> isValid();	
	}
	public function getLastError(){
		return $this -> error;	
	}
	static public function L($key,$replacement=null){
		static $lang = null;
		if(is_null($lang)){
			$lang = require_once(UXLL_TBHELPER_ROOT.'language/tbhelperBase.cn.php');
		}
		return L($key,$lang,$replacement);
	}	
	public function __construct($tbname){
		require_once(UXLL_TBHELPER_ROOT."common/tbDescription.php");
		require_once(UXLL_TBHELPER_ROOT."common/fieldsTypeUI.php");
		require_once(UXLL_TBHELPER_ROOT."common/tbManifest.php");
		
		$this -> tbDescription = new tbDescription($tbname);
		$this -> fieldsTypeUI = new fieldsTypeUI();
		$this -> tbManifest = new tbManifest();
		$this -> tbname = $tbname;
	}
//-----------------------------------------------------------------------------------------

	public function getAddURL(){
		return $this -> _act_url('add');	
	}
	public function getAppendURL(){
		return $this -> _act_url('append');	
	}
	public function getEditURL(){
		return $this -> _act_url('edit');	
	}
	public function getUpdateURL(){
		return $this -> _act_url('update');	
	}
	public function getViewURL(){
		return $this -> _act_url('view');	
	}
	public function getDisplayURL(){
		return $this -> _act_url('display');	
	}
	public function getSearchURL(){
		return $this -> _act_url('search');	
	}
	public function getRemoveURL(){
		return $this -> _act_url('remove');	
	}
	

	public function getFieldsDisplayName(){
		$k = $this -> tbDescription -> getFields();
		$v = $this -> tbDescription -> getFieldName();
		if(is_array($v) && is_array($k) && count($k) === count($v) && $k)return array_combine($k,$v);
		return $v;
	}
	public function getFields(){
		$k = $this -> tbDescription -> getFields();
		return $k;
	}
	public function getDisplayTitle(){
		if(!$this -> _init_check())return false;	

		$caption = $this -> tbDescription -> getCaption();
		return $caption;
	}
	public function getAddFormTitle(){
		if(!$this -> _init_check())return false;	

		$caption = $this -> tbDescription -> getCaption();
		return $this -> L("add form title",array("caption" => $caption));
	}
	public function getEditFormTitle(){
		if(!$this -> _init_check())return false;	

		$caption = $this -> tbDescription -> getCaption();
		return $this -> L("edit form title",array("caption" => $caption));
	}	
	public function getHTML($action,$type,$extraData){
		return $this -> fieldsTypeUI ->  getHTML($action,$type,$extraData);
	}
	public function getType($type){
		return $this -> fieldsTypeUI ->  getType($type);
	}
	public function getFieldName($field=null){
		return $this -> tbDescription -> getFieldName($field);
		
	}
	public function getFieldType($field=null){
		return $this -> tbDescription -> getFieldType($field);
	}
	public function getFieldFlag($field=null){
		return $this -> tbDescription -> getFieldFlag($field);
	}
	public function getFieldLen($field=null){
		return $this -> tbDescription -> getFieldLen($field);
	}	
	
	
	
	
	
	
	
	public function getPrimaryKey() {
		return $this -> tbDescription -> getPrimaryKey();
	}
	
	public function getTbDescripton(){
		return $this -> tbDescription -> getTbDescription($this -> tbname);
	}
//-----------------------------------------------------------------------------------------
	private function _init_check(){
		if(!$this -> tbname){
			$this -> view -> fail("",$this -> L("init function has not executed"),10);
			return false;	
		}
		return true;
	}	
	private function _act_url($act){
		return $act.$this -> tbname;	
	}		
}