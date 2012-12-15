<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: HttpMessage.php
 */
class CHttpMessage extends CHttpRequest{
	private $_URL_MODE;
	private $_URL_GET_CONTROLLER_NAME;
	private $_URL_GET_ACTION_NAME;
	private $_URL_DEFAULT_PAGE_NAME;
	private $_DEFAULT_CONTROLLER_NAME;
	private $_DEFAULT_ACTION_NAME;
	private $_cmd;
	private $_description = array();
	private $_pathinfo = array();//array except control and action
	private $_getinfo = array();

	public function __construct(){
		$this -> _URL_MODE = $GLOBALS['UXLL_CONFIG']['request']['URL_MODE'];
		$this -> _URL_GET_CONTROLLER_NAME = $GLOBALS['UXLL_CONFIG']['request']['URL_GET_CONTROLLER_NAME'];
		$this -> _URL_GET_ACTION_NAME = $GLOBALS['UXLL_CONFIG']['request']['URL_GET_ACTION_NAME'];
		$this -> _URL_DEFAULT_PAGE_NAME = $GLOBALS['UXLL_CONFIG']['request']['URL_DEFAULT_PAGE_NAME'];
		$this -> _DEFAULT_CONTROLLER_NAME = $GLOBALS['UXLL_CONFIG']['request']['DEFAULT_CONTROLLER_NAME'];
		$this -> _DEFAULT_ACTION_NAME = $GLOBALS['UXLL_CONFIG']['request']['DEFAULT_ACTION_NAME'];
		$this -> _parseUri();
	}


	public function getMessage($key=null,$val=null){
		$cmd = array();
		$cmd['control'] = $this -> _cmd['control'] === '' ? $this -> _DEFAULT_CONTROLLER_NAME : $this -> _cmd['control'];
		$cmd['action'] = $this -> _cmd['action'] === '' ? $this -> _DEFAULT_ACTION_NAME : $this -> _cmd['action'];
		$cmd['arguments'] = array_merge($this -> _pathinfo,$this -> _getinfo);
		if(!(is_string($key)))return $cmd;
		switch (strtolower($key)){
			case 'control':
			case 'action':
				return $cmd[$key];	
			case 'arguments':
				if(is_string($val)){
					return  array_key_exists($val,$cmd['arguments']) ? $cmd['arguments'][$val] : '';	
				}
		}
		return $cmd;
	}


	public function setMessage($key,$val){
		return $this -> setMessages(array($key => $val));
	}


	public function setMessages($argv){
		$url = '';
		$description = $this -> getDescription();
		$cmd = $this -> getCmd();
		$pathinfo = $this -> getInfopathArray();
		$getinfo = $this -> getGetinfoArray();
		foreach($argv as $key => $val){
			switch($key){
				case 'control':
				case 'action':
					$cmd[$key] = $val;
					break;
				case 'arguments':
					if(is_array($val)){
						foreach($val as $k => $v){
							if(array_key_exists($k,$pathinfo)){
								if($v)$pathinfo[$k] = $v;
								else unset($pathinfo[$k]);
							}else{
								if($this -> _URL_MODE === 'pathinfo'){
									if($v)$pathinfo[$k] = $v;
									else unset($pathinfo[$k]);							
								}else{
									if($v)$getinfo[$k] = $v;
									else unset($getinfo[$k]);							
								}
							}
														
						}break;
					}
				default:
					return '';
	
			}			
		}

		if($cmd['action'] !== '' && $cmd['control'] === ''){
			$cmd['control'] = $this -> _DEFAULT_CONTROLLER_NAME;
		}
		$_pi = $this -> _buildPathinfo(
			$cmd['control'],
			$cmd['action'],
			$pathinfo
		);
		$_gi = $this -> _buildGetinfo($getinfo);
		if($description['prefix']){
			$url.= '/'.$this -> _URL_DEFAULT_PAGE_NAME;
		}
		$url.= $_pi;
		if($description['pathinfolastslashes']){
			$url.= '/';
		}
		if($description['postfix']){
			if(!$description['pathinfolastslashes']){
				//http://localhost/index.php?test=x&vv=eee
				$url.= '/';
			}
			$url.= $this -> _URL_DEFAULT_PAGE_NAME;
		}
		$url.= $_gi;
		return $url;
	}

	public function getCmd(){
		return $this -> _cmd;
	}


	public function getDescription(){
		return $this -> _description;
	}


	public function getInfopathArray(){
		return $this -> _pathinfo;
	}


	public function getGetinfoArray(){
		return $this -> _getinfo;
	}


	private function _parseUri(){
		$pathinfo = array();
		$cmd = array(
			'control' => '',
			'action' => ''
		);
		$uri = $this -> requestUri();
/*		echo $uri;
		echo '<br>';
		echo '/^\/'.preg_quote($this -> _URL_DEFAULT_PAGE_NAME).'/';*/
		if(preg_match('/^\/'.preg_quote($this -> _URL_DEFAULT_PAGE_NAME).'\//',$uri)){
			$uri = preg_replace('/^\/'.preg_quote($this -> _URL_DEFAULT_PAGE_NAME).'/','',$uri);
			$this -> _description['prefix'] = true;
		}else{
			$this -> _description['prefix'] = false;
		}
		if(preg_match('/\/'.preg_quote($this -> _URL_DEFAULT_PAGE_NAME).'\?/',$uri)){
			$uri = preg_replace('/\/'.preg_quote($this -> _URL_DEFAULT_PAGE_NAME).'\?/','/?',$uri);
			$this -> _description['postfix'] = true;
		}		
		if(preg_match('/\/'.preg_quote($this -> _URL_DEFAULT_PAGE_NAME).'$/',$uri)){
			$uri = preg_replace('/\/'.preg_quote($this -> _URL_DEFAULT_PAGE_NAME).'$/','/',$uri);
			$this -> _description['postfix'] = true;
		}
		if(!array_key_exists('postfix',$this -> _description)){
			$this -> _description['postfix'] = false;
		}


		$uri = explode('?',$uri);
		if(count($uri) === 1){
			$uri[] = '';
		}
		$uri[1] = $this -> parseQueryString($uri[1]);
		if(substr($uri[0],0,1) === '/')$uri[0] = substr($uri[0],1);
		if(substr($uri[0],-1) === '/'){
			$this -> _description['pathinfolastslashes'] = true;
			$uri[0] = substr($uri[0],0,-1);
		}else{
			$this -> _description['pathinfolastslashes'] = false;
		}

		if(empty($uri[0])){
			if(isset($uri[1][$this -> _URL_GET_CONTROLLER_NAME])){
				$cmd['control'] = $uri[1][$this -> _URL_GET_CONTROLLER_NAME];
				unset($uri[1][$this -> _URL_GET_CONTROLLER_NAME]);
			}
			if(isset($uri[1][$this -> _URL_GET_ACTION_NAME])){
				$cresponse['action'] = $uri[1][$this -> _URL_GET_ACTION_NAME];
				unset($uri[1][$this -> _URL_GET_ACTION_NAME]);
			}
		}else{
			$ca = explode('/',$uri[0]);
			$len = count($ca);
			if($len % 2){
				$cmd['control'] = $ca[0];
				$i = 1;
			}else{
				$cmd['control'] = $ca[0];
				$cmd['action'] = $ca[1];
				$i = 2;
			}
			for($x=$i;$x<$len;$x+=2){
				$pathinfo[$ca[$x]] = $ca[$x+1];
			}
		}
		$this -> _pathinfo = $pathinfo;
		$this -> _getinfo = $uri[1];
		$this -> _cmd = $cmd;
	}


	private function _buildGetinfo($g){
		return (!empty($g)) ? '?' . http_build_query($g) : '';
	}


	private function _buildPathinfo($control,$action,$p){
		$url = '';
		if($control !== ''){
			$url .= '/'.$control;
		}
		if($action !== ''){
			$url .= '/'.$action;
		}
		foreach($p as $k => $v){
			$url .= '/' . $k . '/' . $v;
		}
		return $url;
	}
}