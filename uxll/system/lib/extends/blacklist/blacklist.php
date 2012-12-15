<?php
class blacklist extends CModel{
	private $error='';

	public function getLastError(){
		return $this -> error;
	}
	public function getCount($label,$value,$mode="ip"){
		if($mode === "ip"){
			$d = $this -> table('blacklist') -> where("`label`='".$label."' and `ip`='".$value."' and `date` = '".date("Y-m-d")."'") -> select() -> all();
		}else{
			$d = $this -> table('blacklist') -> where("`label`='".$label."' and `value`='".$value."' and `date` = '".date("Y-m-d")."'") -> select() -> all();
		}
		return count($d);		
	}

}