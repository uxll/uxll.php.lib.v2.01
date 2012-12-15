<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: LQ.php
 */
class LQ extends CController{
	private $apikey = '';
	private $login;
	public function getLastError(){
		return $this -> error;
	}
	public function __construct(){
		require_once(MODELROOT.'LQ/view.php');
		require_once(EXTROOT.'login/userAuth.php');
		$this -> apikey = require_once(CONFIGROOT.'apikey.php');
		$this -> login = new userAuth();
		$this -> view = new LQView();
	}	
	public function welcome($msg){
		if(isset($msg[':key']) && $msg[':key'] === $this -> apikey){
			$r = $this -> login -> LQ();
			if($r){
				$this -> view -> go('/privilege');
			}else{
				$this -> view -> fail('',$this -> login -> getLastError(),5);	
			}
		}else{
			$this -> view -> _404();	
		}
	}

}