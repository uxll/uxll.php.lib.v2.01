<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: uxll.lib.php
 */

$GLOBALS['UXLL_CONFIG'] = require_once(CONFIGROOT."uxll.conf.php");
$GLOBALS['UXLL_CONFIG']['language'] = require_once(LANGUAGEROOT.$GLOBALS['UXLL_CONFIG']['language'].".php");
foreach(array(
	"Register",
	"HttpRequest",
	"HttpMessage",
	"Message",
	"IdentityToken",
	"View",
	"Model",
	"functions",
	"FrontControl"
) as $key => $val){
	require_once(LIBROOT.'cores/'.$val.".php");
}//for each
function class_autoload($clsname){
	$filename = LIBROOT.'libraries/'.substr($clsname,1).'.php';
	if(file_exists($filename)){
		require_once($filename);
		return true;
	}
	$filename = LIBROOT.'libraries/'.preg_replace("/^C[A-Z]\w*([A-Z][a-z]+)$/","$1",$clsname).'.php';
	if(file_exists($filename)){
		require_once($filename);
		return true;
	}
	return false;		
}
spl_autoload_register('class_autoload');

R('HTTP',new CHttpMessage());
R('CONTROL',new CFrontControl());
