<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: const.php
 */
define("UXLL_DEBUG_FLAG",true); 
if(UXLL_DEBUG_FLAG){
	error_reporting(E_ALL);
	ini_set("display_errors","On"); 
}else{
	ini_set("display_errors","Off"); 
	error_reporting(0);
}
if(!defined("ROOT"))define("ROOT",str_replace("\\","/",dirname(dirname(dirname(dirname(__FILE__)))))."/");
if(!defined("LIBROOT"))define("LIBROOT",ROOT.'uxll/system/lib/');
if(!defined("MODELROOT"))define("MODELROOT",ROOT.'uxll/models/');
if(!defined("CONFIGROOT"))define("CONFIGROOT",ROOT.'uxll/system/config/');
if(!defined("LANGUAGEROOT"))define("LANGUAGEROOT",ROOT.'uxll/system/languages/');
if(!defined("THIRDLIBROOT"))define("THIRDLIBROOT",ROOT.'uxll/thirdPartyLibraries/');
if(!defined("CONMONROOT"))define("CONMONROOT",ROOT.'uxll/common/');
if(!defined("EXTROOT"))define("EXTROOT",ROOT.'uxll/system/lib/extends/');
if(!defined("THEMEROOT"))define("THEMEROOT",ROOT.'assets/themes/');
if(!defined("HOME"))define("HOME","/");
if(!defined("SYSUIHOME"))define("SYSUIHOME","/uxll/system/lib/ui/");
if(!defined("COMMONUIHOME"))define("COMMONUIHOME","/uxll/thirdPartyLibraries/");
if(!defined("THEMEHOME"))define("THEMEHOME","/assets/themes/");
if(!defined("EXTHOME"))define("EXTHOME","/uxll/system/lib/extends/");
if(!defined("WEB"))define("WEB","http://".$_SERVER["HTTP_HOST"]."/");
require_once(CONFIGROOT."ver.php");