<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: functions.php
 */
function C($configchain,$target=null){
	$r =  is_null($target) ? $GLOBALS['UXLL_CONFIG'] : $target;
	if(strpos($configchain,'/') !== false){
		$cc = explode('/',$configchain);
		for($i=0;$i<count($cc);$i++){
			if(array_key_exists($cc[$i],$r)){$r = $r[$cc[$i]];}
			else{return null;}
		}
		return $r;
	}
	return array_key_exists($configchain,$r) ? $r[$configchain] : null;
}
function L($l,$target=null,$replacement=null){
	$r =  is_null($target) ? $GLOBALS['UXLL_CONFIG']['language'] : $target;
	if(strpos($l,'/') !== false){
		$cc = explode('/',$l);
		for($i=0;$i<count($cc);$i++){
			if(array_key_exists($cc[$i],$r)){$r = $r[$cc[$i]];}
			else{return null;}
		}
		$ret = $r;
	}else{$ret = $r[$l];}

	if(is_array($replacement)){
		foreach($replacement as $key => $value){
			$ret = preg_replace("/\{[$]".$key."\}/",$value,$ret);
		}
	}
	return $ret;

}
function P($var){
	$x = new CDebug($var,true);
	return $x -> get();
}
function R($key="",$val=""){
	$r = CRegister::getInstance();
	if($key != "" && $val != "")return $r -> save($key,$val);
	if($key != ""){
		if(strpos($key,'/') !== false){
			$cc = explode('/',$key);
			$r =  $r -> read($cc[0]);
			for($i=1;$i<count($cc);$i++){
				$r = $r -> $cc[$i]();
			}
			return $r;
		}
		return $r -> read($key);
	}
	return $r;
}
function S($key="",$val=null,$mode=null){
	$r = CSession::getInstance();
	if($key != "" && $val !== null)return $r -> set($key,$val);
	if($key != ""){
		if($mode === 'unset')
		return $r -> set($key,null);
		else
		return $r -> get($key);
	}
	return $r;
}