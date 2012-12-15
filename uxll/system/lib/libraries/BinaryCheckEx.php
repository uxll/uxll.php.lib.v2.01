<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: BinaryCheckEx.php
 */
class CBinaryCheckEx{
	private $size;
	private $binCheck;
	private $data;
	public function __construct($data){
		$this -> size = 3;
		$this -> binCheck = array();
		$this -> data = $data;
		for($i=0;$i<ceil(count($data) / $this -> size);$i++){
			$this -> binCheck[$i] = 0;
		}
	}
	public function check($index){
		return (1 << ($index % $this -> size)) & $this -> binCheck[floor($index / $this -> size)];
	}
	public function set($index,$checked){
		if($checked){
			if(!$this -> check($index))
			$this -> binCheck[floor($index / $this -> size)] += 1 << ($index % $this -> size);
		}else{
			if($this -> check($index))
			$this -> binCheck[floor($index / $this -> size)] -= 1 << ($index % $this -> size);
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
				$ret[] = $this -> $i;
			}
		}
		return $ret;
	}	
}