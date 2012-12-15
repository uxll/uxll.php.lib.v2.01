<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: model.php
 */
class commentModel extends CModel{

	private $error = 0;
	private $engine;
	public function getLastError(){
		return $this -> error;
	}	
	public function addcomment($msg){
		$msg -> turnOn();
		$this -> table('comment') -> append(array(
			'location' => $msg['location'],
			'content' => $msg['comment']
		));
		if(parent::isValid()){
			return true;	
		}else{
			$this -> error = parent::getLastError();
			return false;
		}
	}
}