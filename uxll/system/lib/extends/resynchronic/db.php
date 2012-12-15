<?php
class resynchronicDB extends CModel{
	private $error;
	public function get($tbname){
		die(base64_encode(
			json_encode(
			array(
				"tbname" => $tbname,
				"content" => $this -> table($tbname)
				-> where("1=1")
				-> select()
				-> all()
			)
		)));	
	}
	public function set($base64_data){
		$data = json_decode(base64_decode($base64_data),true);
		if(!$data){
			$this -> error = "来源数据格式不正确";	
			return false;
		}

		$tbname = $data['tbname'];
		if($data['content']){
			echo "正在同步表:".$tbname.",共(".count($data['content']).")条记录<br>";
			$this -> clr($tbname);
			foreach($data['content'] as $row){
				$this -> table($tbname)
				-> append($row);
				#var_dump($row);
			}
			
		}else{

			$this -> error = "来源数据格式不正确";	
			return false;

		}
		if(!$this -> isValid()){
			$this -> error = $this -> getLastError();
			return false;	
		}
		return true;
	}
	private function clr($tbname){
		$this -> q("TRUNCATE TABLE `".$tbname."`");
	}
}