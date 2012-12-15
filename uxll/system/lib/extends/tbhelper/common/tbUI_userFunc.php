<?php
class tbUIUserFunc{
	private $text = array();
	private $fn = array();
	private $mask = array();
	private $isValid = false;
	
	public function isValid(){
		return $this -> isValid;	
	}
	public function getFunctionName(){
		return $this -> fn;
	}
	public function getMask(){
		return $this -> mask;
	}
	public function getText(){
		return $this -> text;
	}	
	
	public function analyse($operation_string){
		if(!is_string($operation_string)){
			return false;
		}
		$tfa = explode(";",$operation_string);
		for($i=0; $i<count($tfa); $i++){
			$tfa_item = explode("|",$tfa[$i]);
			if(count($tfa_item) === 3){
				$this -> fn[] = $tfa_item[0];
				$this -> text[] = $tfa_item[1];
				$m = explode(",",$tfa_item[2]);
				if(is_array($m)){
					$this -> mask[] = $m;
					$this -> isValid = true;
				}
			}else{
				$this -> isValid = false;
				return false;	
			}	
		}
	}
}