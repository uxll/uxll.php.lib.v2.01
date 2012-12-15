<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: ui.php
 */
class privilegeUI{
	private $header ='';
	private $bodyhtml ='';
	private $diyjs = '';
	private $privilege = null;
	private $modelhome;
	public function __construct($header,$bodyhtml,$diyjs){
			$this -> header = $header;
			$this -> bodyhtml = $bodyhtml;
			$this -> diyjs = $diyjs;
	}
	public function setCurrentTheme($theme) {
		$this -> theme = $theme;
	}
	public function setThisHome($home) {
		$this -> modelhome = $home;
	}
	public function getThisHome(){
		return $this->modelhome;
	}
	public function getCurrentTheme() {
		return $this -> theme;
	}
	public function getheader(){
		return $this -> header;	
	}

	public function getbodyhtml(){
		return $this -> bodyhtml;	
	}
	public function getdiyjs(){
		return $this -> diyjs;	
	}
	public function setprivilege($privilege) {
		$this -> privilege = $privilege;
	}
	public function getprivilege() {
		return $this -> privilege;
	}
}