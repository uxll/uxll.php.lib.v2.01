<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Cache.php
 */
class CCache{
	private function __construct(){}
	
	static public function removeDir($dir){
		if(!$handle = @opendir($dir)){
			return false;
		}
		@readdir($handle);
		@readdir($handle);
		while (false !== ($file = @readdir($handle))) {
			$file = $dir.DIRECTORY_SEPARATOR.$file;
			if (is_dir($file)){
				self::removeDir($file);
				@rmdir($file);
			} else {
				@unlink($file);
			}
		}
		@closedir($handle);
		return true;
	}
	static public function mkdirs($path,$mode=0755){
		if(!is_dir($path)){
			self::mkdirs(dirname($path),$mode);
			mkdir($path,$mode);
		}
		return true;
	}
	static public function update($path,$filename,$content){
		$path = iconv('UTF-8','GB2312',$path);
		$filename = iconv('UTF-8','GB2312',$filename);
		if(!is_dir($path)){
			self::mkdirs($path);
		}
		return file_put_contents($path.$filename,$content);
	}
	static public function append($path,$filename,$content){
		$path = iconv('UTF-8','GB2312',$path);
		$filename = iconv('UTF-8','GB2312',$filename);
		if(!is_dir($path)){
			self::mkdirs($path);
		}
		return file_put_contents($path.$filename,$content,FILE_APPEND);
	}	
	static public function read($absfilename){
		$absfilename = iconv('UTF-8','GB2312',$absfilename);
		return file_get_contents($absfilename);	
	}
	static public function rename($current,$dest){
		$cur = iconv('UTF-8','GB2312',$current);
		$dest = iconv('UTF-8','GB2312',$dest);
		return rename($cur,$dest);
	}
	static public function romove($currentpath,$filename){
		$cur = iconv('UTF-8','GB2312',$currentpath);
		$old = iconv('UTF-8','GB2312',$filename);
		if(is_dir($cur.$old.'/')){
			self::removeDir($cur.$old.'/');
		}else{
			@unlink($cur.$old);	
		}
		return true;
	}
	static public function duplicate($source,$destination,$child = true){
		if(!is_dir($source)){
			return 0;
		}
		if(!is_dir($destination)){
			mkdir($destination,0777);
		}
		$handle=dir($source);
		while($entry=$handle->read()){
			if(($entry!=".")&&($entry!="..")){
				if(is_dir($source."/".$entry)){
				if($child)
					CCache::duplicate($source."/".$entry,$destination."/".$entry,$child);
				}else{
					copy($source."/".$entry,$destination."/".$entry);
				}
			}
		}
		return 1;
	}
}