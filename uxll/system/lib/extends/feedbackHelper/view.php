<?php
class feedbackHelperView extends CView {
	public function __construct(){
		$this -> engine = $this -> getEngine();
	}
 	public function add($config,$fieldsTypeUI,$tpl="") {
 		$this -> engine -> assign("config",$config);
 		$this -> engine -> assign("fieldsTypeUI",$fieldsTypeUI);
 		if($tpl === ""){
 			$tpl = UXLL_ROOT."extends/feedbackHelper/template/add.tpl";
 		}
 		return $this -> engine -> fetch($tpl);
 	}
 	public function displayFeedback($config,$item,$tpl="") {
 		$this -> engine -> assign("config",$config);
 		$this -> engine -> assign("item",$item);
 		if($tpl === ""){
 			$tpl = UXLL_ROOT."extends/feedbackHelper/template/item.tpl";
 		}
 		return $this -> engine -> fetch($tpl);
 	}
}