<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: BinaryCheck.php
 */
class CBinaryCheck{
	//index start at 0
	private $binCheck;
	private $data;
	public function __construct($data){
		$this -> data = $data;
		$this -> binCheck = 0;
	}
	private function checkIndex($index){
		return (is_int($index) && $index >= 0);
	}
	public function check($index){
		if(! $this -> checkIndex($index))return false;
		return (1 << $index) & $this -> binCheck;
	}
	public function set($index,$checked){
		if(! $this -> checkIndex($index))return false;
		if($checked){
			if(!$this -> check($index))
			$this -> binCheck += 1 << $index;
		}else{
			if($this -> check($index))
			$this -> binCheck -= 1 << $index;
		}
	}
	public function getItems(){
		$ret = array();
		for($i=0;$i<count($this -> data);$i++){
			if($this -> check($i)){
				$ret[] = $this -> data[$i];
			}
		}
		return $ret;
	}
	public function getIndexs(){
		$ret = array();
		for($i=0;$i<count($this -> data);$i++){
			if($this -> check($i)){
				$ret[] = $i;
			}
		}
		return $ret;
	}
	public function getValue(){
		return $this -> binCheck;
	}
	public function setValue($cksum){
		if(! $this -> checkIndex($cksum))return false;
		$this -> binCheck = $cksum;
		return $this;
	}
	public function add($index,$value=0){
		//start at left in binary string
		//this index equal array's index
		if(! $this -> checkIndex($index))return false;
		if((int)$value !== 0)$value = '1';
		$binCheck = decbin($this -> getValue());
		$binCheck = substr($binCheck,0,$index) . $value . substr($binCheck,$index);
		return bindec($binCheck);
	}
	public function delete($index){
		if(! $this -> checkIndex($index))return false;
		$binCheck = decbin($this -> getValue());
		$binCheck = substr($binCheck,0,$index) . substr($binCheck,$index+1);
		return bindec($binCheck);
	}
	public function reset(){
		$this -> binCheck = 0;	
	}
}
/*
//100001
//left 1 is ff
//right 1 is a
$x = new ReflectionClass('CBinaryCheck');
$test = array('a','b','c','d','e','f','ff');
$y = $x -> newInstance($test);
$y -> set(1,0);
$y -> set(1,0);
$y -> set(4,0);
$y -> set(3,0);
$y -> set(1,1);
$y -> set(6,1);
//$y -> debug();
$v = $y -> getValue();
echo $v;
print_r($y -> getItems($v));*/