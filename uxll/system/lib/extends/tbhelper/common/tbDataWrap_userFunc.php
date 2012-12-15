<?php
class tbDataWrapUserFunc{

	private $fn = array();

	private $isValid = false;
	public function __construct($operation_string){
		if(!is_string($operation_string)){
			return false;
		}
		$tfa = explode(";",$operation_string);
		for($i=0; $i<count($tfa); $i++){
			$this -> fn[] = $tfa[$i];
			$this -> isValid = true;
		}
	}
	public function isValid(){
		return $this -> isValid;	
	}
	public function getFunctionName(){
		return $this -> fn;
	}

}