<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-18 
 *  @author: uxll@qq.com
 *  @file: lang.php
 */
class lang{
	protected function l($msg,$replacement=array()){
		foreach($replacement as $key => $value){
			$msg = preg_replace("/\{[$]".$key."\}/",$value,$msg);
		}	
		return $msg;	
	}
}