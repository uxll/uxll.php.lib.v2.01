<?php
class tbPrivMask extends CModel{
	//accept three types date, string all,array fields such as array(field2,field5) and int mask
	private $tbname;
	private $privMask;
	private $error;
	private $isValid = false;
	private $hash_masked_table = array();
	public function getFields($tb_describe,$name="Field"){
		
		$k = array();
		foreach($tb_describe as $val){
			$k[] = $val[$name];
			#var_dump($val[$name]);
		}
		return $k;
	}
	public function getMaskedDescripton($tbname,$privMask){
		$tb_describe = $this -> getTableDecription($tbname);
		switch (gettype($privMask)) {
			case "integer":
				return $this -> calcIntMask($tb_describe,$privMask);
			case "string":
				return $this -> calcStringMask($tb_describe,$privMask);
			case "array":
				return $this -> calcArrayMask($tb_describe,$privMask);
		}
		return false;
	}
//---------------------------------------------------------------------------------	


	private function calcIntMask($tb_describe,$mask){
		if(!is_array($tb_describe)){
			return false;
		}
		$rv = array();

		for($i=0,$I=count($tb_describe);$i<$I;$i++){
			if(1<<($I-$i-1)&$mask){
				$rv[] = $tb_describe[$i];
			}
		}
		$this -> hash_masked_table[$this -> tbname] = $rv;
		return $rv;			
	}
	private function calcArrayMask($tb_describe,$mask){
		if(!is_array($mask)){
			return false;
		}
		#echo P($mask);
		$rv = array();
		foreach($tb_describe as $val){
			if(in_array($val["Field"],$mask)){
				$rv[] = $val;//$val["Field"]	
			}
		}
//		$ret = array();
//		#echo P($rv);
//		foreach($mask as $item){
//			if(array_key_exists($item,$rv)){
//				$ret[] = $rv[$item];	
//			}
//		}
		$this -> hash_masked_table[$this -> tbname] = $rv;
		return $rv;
	}
	private function calcStringMask($tb_describe,$mask){
		if($mask !== 'all'){
			return false;
		}
		$this -> hash_masked_table[$this -> tbname] = $tb_describe;
		return $tb_describe;
	}
}