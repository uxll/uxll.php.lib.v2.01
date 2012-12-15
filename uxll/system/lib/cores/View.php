<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: View.php
 */
class CView{
	private $engine = null;
	public function __construct(){
	}
	protected function getEngine(){
		if(is_null($this -> engine)){
			$this -> engine = new CTemplate;
		}
		return $this -> engine;
	}
	protected function getThisHome(){
		return HOME.'uxll/models/'.preg_replace("/View$/","",get_class($this)).'/';
	} 
	public function go($url){
		header('location:'.$url);
	}
	public function goback(){
		$url = CHttpRequest::getInstance();
		$url = $url -> frontUrl();	
		header('location:'.$url);
	}	
	public function redirect($url,$msg,$delay = 0,$state=null,$emergency=null){
		echo $this -> redirect_html($url,$msg,$delay,$state,$emergency);
		exit;
	}
	public function _404(){
		$this -> getEngine();
		$this -> engine -> assign('language',$GLOBALS['UXLL_CONFIG']['language']);
		$this -> engine -> display(LIBROOT.'ui/tpl/404.tpl');
		@header('HTTP/1.x 404 Not Found');
		@header('Status: 404 Not Found');
		exit;
	}    
	public function redirect_html($url,$msg,$delay = 0,$state=null,$emergency=null){
	
		if(!$url){
			$url = CHttpRequest::getInstance();
			$url = $url -> frontUrl();	
		}
		
		$this -> getEngine();
		$this -> engine -> assign('url',$url);
		$this -> engine -> assign('msg',$msg);
		$this -> engine -> assign('emergency',$emergency);
		$this -> engine -> assign('redirect',$GLOBALS['UXLL_CONFIG']['language']['redirect_time_decrease']);
		$this -> engine -> assign('delay',$delay);
		$this -> engine -> assign('state',$state);
		$this -> engine -> assign('language',$GLOBALS['UXLL_CONFIG']['language']);
		return $this -> engine -> fetch(LIBROOT.'ui/tpl/showMessage.tpl');
	} 
	public function success($url,$msg,$delay = 0,$emergency=null){
		$this -> redirect($url,$msg,$delay,'successful/'.rand(1,5),$emergency);
	}
	public function fail($url,$msg,$delay = 0,$emergency=null){
		$this -> redirect($url,$msg,$delay,'failed/'.rand(1,5),$emergency);
	}
	public function success_html($url,$msg,$delay = 0,$emergency=null){
		return $this -> redirect_html($url,$msg,$delay,'successful/'.rand(1,5),$emergency);
	}
	public function fail_html($url,$msg,$delay = 0,$emergency=null){
		return $this -> redirect_html($url,$msg,$delay,'failed/'.rand(1,5),$emergency);
	}
	public function response($url,$msg,$status=true){
		$mode = CHttpRequest::getInstance() -> isAjax();//true is ajax
		if($mode){
			return json_encode(array(
				'status' => $status ? 'success' : 'error',
				'message' => strip_tags($msg)
			));	
		}else{
			if($status){
				return $this -> success_html($url,$msg,1);
			}else{
				return $this -> fail_html($url,$msg,10);
			}
		}
	}		
}