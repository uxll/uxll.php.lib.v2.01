<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: FrontControl.php
 */
interface ICommand{
	function execute();
	function undo();	
}
interface IController{
	function checkPrivilege(CMessage $msg,CIdentityToken $it);
}
interface IMessageRewrite{
	function rewrite(CMessage $msg);
}
abstract class CAuthController implements IController{
	public function checkPrivilege(CMessage $msg,CIdentityToken $it){
		return false;
	}

}
abstract class CController implements IController{
	public function checkPrivilege(CMessage $msg,CIdentityToken $it){
		//block some guest via checking ip form CIdentityToken ;
		return true;
	}
}
class CFrontControl{
	private $view;
	private $msg;

	private $control;
	private $action;

	private $roleInfo;

	public function __construct(){
		$this -> view = new CView();
		$this -> roleInfo = new CIdentityToken();
	}
	public function __call($name,$arguments){
		return false;
	}

	public function route(CMessage $msg){
		if(!$msg -> isValid()){
			$this -> error('Message is invalid!');
			return false;
		}
		if (!class_exists($msg -> getControl())) {
			if(!isset($GLOBALS['UXLL_CONFIG']['route_table'])){
				$GLOBALS['UXLL_CONFIG']['route_table'] = require_once(CONFIGROOT.'routeTable.php');
			}
			
			if(
				array_key_exists($this -> control,$GLOBALS['UXLL_CONFIG']['route_table']) && 
				file_exists($GLOBALS['UXLL_CONFIG']['route_table'][$msg -> getControl()])
			){
				require_once($GLOBALS['UXLL_CONFIG']['route_table'][$msg -> getControl()]);
				if (!class_exists($msg -> getControl())){
					$this -> routeErr();
					return false;
				}
			}elseif(file_exists(MODELROOT.$msg -> getControl().'/'.$msg -> getControl().'.php')){
				require_once(MODELROOT.$msg -> getControl().'/'.$msg -> getControl().'.php');
				if (!class_exists($msg -> getControl())){
					$this -> routeErr();
					return false;
				}
			}else{
				if($this -> rebuildMsg($msg) !== true){
					$this -> routeErr($msg -> getControl());
					return false;	
				}
				if (!class_exists($msg -> getControl())){
					$this -> routeErr();
					return false;
				}
			}
		}
		
		$rc = new ReflectionClass($msg -> getControl());
		if ($rc -> implementsInterface('IController')) {
			if(!$rc -> hasMethod(C('action_not_found')) && !$rc -> hasMethod($msg -> getAction())){
				$this -> error(L("FrontControl/routeerror").':'.$msg -> getAction());
				return false;
			}else{
				$controller = $rc -> newInstance();
				//privilege check start
				if(get_parent_class($controller) === 'CAuthController'){
					$r = $controller -> checkPrivilege($msg,$this -> roleInfo);
				}else if(get_parent_class($controller) === 'CController'){
					$r = $controller -> checkPrivilege($msg,$this -> roleInfo);
				}else{
					$this -> accessDeny();
					return false;
				}
				if($r === false){
					$this -> accessDeny();
					return false;
				}
				
				if($rc -> hasMethod($msg -> getAction())){
					$action = $msg -> getAction();
				}else{
					$action = C('action_not_found');	
				}
				$method = $rc -> getMethod($action);
				$method -> invokeArgs($controller, array($msg));
				return true;
			}
		} else {
			$this -> error(L("FrontControl/classnotfound"));
			return false;
		}
	}


	
	private function _404(){
		$this -> view -> _404();
	}
	private function routeErr(){
		$this -> _404();
		return;
	}
	private function error($msg){
		$e = $this -> view -> fail(HOME,$msg,120);
		$e -> halt();
	}
	private function accessDeny(){
		$e = $this -> view -> fail(HOME,L("FrontControl/accessdeny"),120);
		$e -> halt();
	}
	private function rebuildMsg(CMessage $msg){
		require_once(C('msg_rewrite'));
		$rc = new ReflectionClass('msgRewrite');
		if ($rc -> implementsInterface('IMessageRewrite')) {
			$controller = $rc -> newInstance();
			$controller -> rewrite($msg);
#			echo P($msg);
			return $controller -> isValid();
		}
		return false;
	}
}