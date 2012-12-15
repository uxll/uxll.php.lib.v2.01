<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Upload.php
 */
class CUpload{
	
	private $isValid = false;
	private $error = '';
	private $file_name = '';
	private $uploaddir = 'assets/uploads/';
	private $uptypes = array('image/jpg','image/jpeg','image/png','image/pjpeg','image/gif','image/bmp','image/x-png');
	private $max_file_size = 2000000;
	public function __construct(){
		if(defined('UXLL_UPLOAD_IMAGES_PATH')){
			$this -> uploaddir	= UXLL_UPLOAD_IMAGES_PATH;
		}
	}
	public function isValid(){
		return $this -> isValid;
	}
	public function getLastError(){
		return $this -> error;
	}
	public function upload($field_name,$newname=''){
		if(array_key_exists($field_name,$_FILES)){
			if(!$_FILES[$field_name]['tmp_name']){
				$this -> error = "no upload file!\n";
				$this -> isValid = false;
				return false;
			}
			if(!in_array($_FILES[$field_name]['type'],$this -> uptypes)){
				$this -> error = "illegal file type!\n";
				$this -> isValid = false;
				return false;
			}
			if($_FILES[$field_name]['size'] > $this -> max_file_size){
				$this -> error = "This file's size exceed its allowed!\n";
				$this -> isValid = false;
				return false;
			}
			$filename = $newname ? $newname : basename($_FILES[$field_name]['name']);
			$uploadfile = ROOT. $this -> uploaddir . $filename;
			if($newname !== ""){
				$new_file_ext = pathinfo($_FILES[$field_name]['name']);
				$replacement_file_ext = pathinfo($uploadfile);
				if(strtolower($new_file_ext["extension"]) !== strtolower($replacement_file_ext["extension"])){
					$this -> error = "替换文件的基本条件是文件扩展名一样，GIF的要用GIF的替换";
					$this -> isValid = false;
					return false;
				}
			}
			
			if (@move_uploaded_file($_FILES[$field_name]['tmp_name'], $uploadfile)) {
				$this -> file_name = '/assets/uploads/images/'.$filename;
				$this -> isValid = true;
				return true;
			} else {
				$this -> error = "Possible file upload attack!\n";
				$this -> isValid = false;
				return false;
			}

		}
		$this -> error = "no upload file";
		$this -> isValid = false;
		return false;
	}
	public function getUploadFileName(){
		return $this -> file_name;
	}
}