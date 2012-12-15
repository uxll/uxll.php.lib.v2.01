<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: view.php
 */
class loginView extends CView{
	private $engine;
	private $error = 0;
	public function getLastError(){
		return $this -> error;
	}
	public function __construct(){
		$this -> engine = $this -> getEngine();
	}
	public function showLoginUI(userAuth $login){
		$login -> showLoginUI($this -> engine,$login -> getLastError());
	}
}