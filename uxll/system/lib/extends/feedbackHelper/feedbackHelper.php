<?php
class feedbackHelper{
	private $error='';
	private $engine;
	public function getLastError(){
		return $this -> error;
	}	
	public function __construct() {
		require_once(UXLL_ROOT."extends/tbhelper/common/fieldsTypeUI.php");
		require_once(UXLL_ROOT."extends/feedbackHelper/view.php");
		require_once(UXLL_ROOT."extends/feedbackHelper/model.php");
		$this -> engine = new fieldsTypeUI();
		$this -> view = new feedbackHelperView();
		$this -> model = new feedbackHelperModel();
	}
	public function add($config,$tpl="") {
		return $this -> view -> add($config,$this -> engine,$tpl);
	}
	public function append($msg,$label="feedback") {
		if(!$msg -> getData()){
			return false;	
		}
		$r = $this -> model -> appendFeedback($msg -> getData(),$label);
		if(!$r){
			$this -> error = $this -> model -> getLastError();
			return false;	
		}
		return true;
	}
	public function verify($msg) {
		$where = $this -> _pks2towhere($msg,'id');
		$r = $this -> model -> verifyFeedback($where);
		if(!$r){
			$this -> error = $this -> model -> getLastError();
			return false;	
		}
		return true;
	}
	public function display($config,$label="feedback",$tpl="") {
		$data = $this -> model -> getFeedbackByLabel($label);
		$ret = array();
		foreach ($data["rs"] as $v) {
			if(@($item = json_decode($v['msg'],true))){
				$ret[] = $this -> view -> displayFeedback($config,$item,$tpl);	
			}
		} 
		return join("",$ret).$data['html'];
	}
	public function displayForce($config,$label="feedback",$tpl="") {
		$data = $this -> model -> getFeedbackByLabelForce($label);
		$ret = array();
		foreach ($data["rs"] as $v) {
			if(@($item = json_decode($v['msg'],true))){
				$ret[] = $this -> view -> displayFeedback($config,$item,$tpl);	
			}
		} 
		return join("",$ret).$data['html'];
	}
	public function getDisplayData($label) {
		$data = $this -> model -> getFeedbackByLabel($label);
		$ret = array();
		foreach ($data["rs"] as $v) {
			if(@($item = json_decode($v['msg'],true))){
				$ret[] = $item;	
			}
		} 
		return array($data["html"],$ret);
	}
	private function _pks2towhere($msg,$pkname){
		$pks = $msg[':pk'];
		$ret = array();
		$pks = explode(",",$pks);
		$v = new CIntegerValidator();
		foreach($pks as $pk){
			if($v -> isValid($pk)){
				$ret[] = "`".$pkname."` = ".$pk;	
			}	
		}
		return join(" or ",$ret);
	}	
}