<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: divide.php
 */
if(!defined("ROOT"))exit();
require_once(CONFIGROOT."pcdebugwidth.php");
function isPc(){
	if(!isset($_SERVER['HTTP_USER_AGENT']))return false;
	$ua = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match("/\bwindows\b/i",$ua)){
		return true;
	}else{
		return false;
	}
}
function advancedMobile() {
	if(!isset($_SERVER['HTTP_USER_AGENT']))return 220;
	if(!isset($_SERVER['HTTP_ACCEPT']))return 220;
	$ua = $_SERVER['HTTP_USER_AGENT'];
	
	if(preg_match("/\bandroid\b/i",$ua)){
		return 320;
	}
	if(preg_match("/\bwindows\b/i",$ua)){
		return PCDEBUGWIDTH;
	}
	if(preg_match("/\bios\b/i",$ua) && preg_match("/\bsafari\b/i",$ua)){
		return 320;
	}
	if(preg_match("/\biphone\b/i",$ua) && preg_match("/\bsafari\b/i",$ua)){
		return 320;
	}
	return 220;
}