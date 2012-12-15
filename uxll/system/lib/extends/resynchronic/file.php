<?php
class resynchronicFile{
	private $error;
	static private $result = array();
	public function get($path_folder,$cond){
		/**
			第一个参数是文件目录路径 必须以/结尾
			第二个参数是这个目录下哪些文件要被同步
				格式
					1.include:name1|name2
					2.eliminate:exceptfilename1|exceptfilename2
					3.date:24	---> 24为小时,表示更新日期在最近24小时以内
					
		*/
		$ret = array();
		$cond = $this -> parseCondition($path_folder,$cond);
		if($cond === false){
			$this -> error = "参数COND不合法:".$cond;
			$this -> out($ret);
			return false;
		}
		$this -> reset();
		switch($cond[1]){
		
			case "date":
			
				self::getLastUpdateFiles($path_folder,$cond[2]);
				foreach(self::$result as $item){
					$ret[] = array(
						"path" => $item,
						"content" => base64_encode(file_get_contents($item))
					);	
				}
				$this -> out($ret);
				break;
			case "include":
				self::getIncludedFiles($path_folder,$this -> parseCondition($cond[2]));
				foreach($this -> result as $item){
					$ret[] = array(
						"path" => $item,
						"content" => base64_encode(file_get_contents($item))
					);	
				}
				$this -> out($ret);
				break;
			case "eliminate":
				self::getEliminatedFiles($path_folder,$this -> parseCondition($cond[2]));
				foreach($this -> result as $item){
					$ret[] = array(
						"path" => $item,
						"content" => base64_encode(file_get_contents($item))
					);	
				}
				$this -> out($ret);
				break;
			default:
				
				$this -> out($ret);
				break;
		}
	}
	public function set($base64_data){
		$arr = @json_decode(base64_decode($base64_data),true);
		if(!$arr){
			if(is_null($arr)){
				$this -> error = "从服务器发过来的数据解码错误";
				die($this -> error);					
			}
			return false;	
		}else{
			foreach($arr as $item){
				$path = pathinfo($item['path']);
				$this -> echoImmediatelyInfo("正在升级文件:".$item['path']);
				CCache::update($path['dirname'].'/',$path['basename'],base64_decode($item['content']));
			}
		}		
		return true;
	}
	public function getLastError(){
		return $this -> error;	
	}
	static private function getLastUpdateFiles($dir,$hours){
		if(!is_dir($dir))return false;
		if(substr($dir,-1) === '/'){
			$dir = substr($dir,0,-1);	
		}
		$handle = @opendir($dir);
		@readdir($handle);
		@readdir($handle);
		while (false !== ($file = @readdir($handle))) {
			$file = $dir.DIRECTORY_SEPARATOR.$file;
			if (is_dir($file)){
				self::getLastUpdateFiles($file,$hours);
			} else {
				if((time() - filemtime($file)) < $hours * 60 * 60){
					array_push(self::$result,self::tounixpath($file));	
				}else if((time() - filectime($file)) < $hours * 60 * 60){
					array_push(self::$result,self::tounixpath($file));	
				};
			}
		}
		@closedir($handle);
		return true;
	}
	static private function getEliminatedFiles($dir,$eliminatedlists){
		if(!is_dir($dir))return false;
		if(substr($dir,-1) === '/'){
			$dir = substr($dir,0,-1);	
		}
		$handle = @opendir($dir);
		@readdir($handle);
		@readdir($handle);
		while (false !== ($file = @readdir($handle))) {
			$file = $dir.DIRECTORY_SEPARATOR.$file;
			if (is_dir($file)){
				self::getEliminatedFiles($file,$hours);
			} else {
				if(!in_array(self::tounixpath($file),$eliminatedlists)){
					array_push(self::$result,self::tounixpath($file));	
				};
			}
		}
		@closedir($handle);
		return true;
	}	
	static private function getIncludedFiles($dir,$includedlists){
		if(!is_dir($dir))return false;
		if(substr($dir,-1) === '/'){
			$dir = substr($dir,0,-1);	
		}
		$handle = @opendir($dir);
		@readdir($handle);
		@readdir($handle);
		while (false !== ($file = @readdir($handle))) {
			$file = $dir.DIRECTORY_SEPARATOR.$file;
			if (is_dir($file)){
				self::getIncludedFiles($file,$hours);
			} else {
				if(in_array(self::tounixpath($file),$includedlists)){
					array_push(self::$result,self::tounixpath($file));	
				};
			}
		}
		@closedir($handle);
		return true;
	}
	static private function tounixpath($path){
		return str_replace("\\","/",$path);
	}
	private function parseListCmd($linecmd){
		$ret = array();
		if(preg_match("/^(?:include|eliminate)(.*)$/",$linecmd,$matches)){
			if(isset($matches[1]) && is_string($matches[1])){
				$arr = str_split("|",$matches[1]);
				if(count($arr)){
					foreach($arr as $p){
						if(is_string($p) && strlen($p) && substr($p,0,1) === '/'){
							$ret[] = $path_folder.substr($p, 1);	
						}
					}	
				}
			}
		}
		return $ret;
	}
	private function parseCondition($path_folder,$cond){
		if(preg_match("/^(date|eliminate|include):(.*)$/",$cond,$matches)){
			return $matches;
		}
		return false;
	}
	private function reset(){
		self::$result = array();	
	}
	private function out($ret){
		foreach($ret as &$r){
			$r['path'] = str_replace(ROOT, '', $r['path']);
		}
		echo(base64_encode(json_encode($ret)));	
	}
	public function echoImmediatelyInfo($info){
		static $f = 0;
		if($f){
			echo str_pad(" ", 256 * 16);
			$f = 1;
		}
		echo $info;
		echo "<br>";
		ob_flush();  
		flush();
	}	
}