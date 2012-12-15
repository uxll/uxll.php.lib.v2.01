<?php
class feedbackHelperModel extends CModel {
	public function getFeedbackByLabel($label) {
		$v = $this -> table("feedback") -> where("`label`='".$label."' and `verification` = 'show'")
		-> page(5,5)
		-> order("`id` desc")
		;
		return array(
			"html" => $v -> getPageHTML()
			,"rs" => $v -> select() -> all()
		);
		
	} 
	public function getFeedbackByLabelForce($label) {
		$v = $this -> table("feedback") -> where("`label`='".$label."'")
		-> page(15,5);
		return array(
			"html" => $v -> getPageHTML()
			,"rs" => $v -> select() -> all()
		);
		
	} 
	public function appendFeedback($data,$t) {
		$v = addslashes(json_encode($data));
		$this -> table("feedback") -> append(array(
			"label" => $t
			,"msg" => $v
		));
		$r = parent::isValid();
		if(!$r){
			$this -> error = CModel::getLastError();
			return false;	
		}
		return true;
	}
	public function verifyFeedback($where) {
		if($where){
			parent::update(array("verification" => "show"),$where);
			$r = parent::isValid();
			if(!$r){
				$this -> error = CModel::getLastError();
				return false;	
			}
			return true;
		}else{
			$this -> error = "where is illegal:".$where;	
			return false;
		}
	}	
}