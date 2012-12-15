<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: login.php
 */
class login extends CController{
	private $error='';
	private $login;
	public function getLastError(){
		return $this -> error;
	}
	public function __construct(){
		require_once(MODELROOT.'login/view.php');
		require_once(EXTROOT.'login/userAuth.php');
		$this -> view = new loginView();
		$this -> login = new userAuth();
	}	
	public function welcome($msg){
		return $this -> check($msg);
	}
	public function check($msg){
		$r = $this -> login -> check($msg);
		if($r){
			$this -> view -> go('/privilege');
		}else{
			$this -> view -> showLoginUI($this -> login);
		}
	}
	public function logout(){
		$this -> login -> logout();
		$this -> view -> go('/login');
	}	
	public function md5($str){
		die(md5($str[':p']));	
	}
}