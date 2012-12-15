<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: HttpRequest.php
 */
class CHttpRequest{
	private static $_instance = null;
	private $post;
	private $get;
	private $request;
	private $user_post_data;
	private function __construct(){
		$this -> post = $this -> _magicQuotes($_POST);
		$this -> get = $this -> _magicQuotes($_GET);
		$this -> request = $this -> _magicQuotes($_REQUEST);
	}
	static public function getInstance(){
		if (null === self::$_instance){
			self::$_instance = new self();
		}
		return self::$_instance;
	}
	public function argv($offset=null,$default=null){
		if (null === $offset){
			return $_SERVER['argv'];
		}else{
			return (isset($_SERVER['argv']) && isset($_SERVER['argv'][$offset])) ? $_SERVER['argv'][$offset] : $default;
		}
	}
	public function files($name=''){
		if (!$name){
			return $_FILES;
		}else{
			return isset($_FILES[$name]) ? $_FILES[$name] : null;
		}
	}
	public function server($name,$default=null){
		return isset($_SERVER[$name]) ? $_SERVER[$name] : $default;
	}
	public function setPost($name,$value=''){
		if (!is_array($name)){
			$this -> post[$name] = $this -> _magicQuotes($value);
		}else{
			$this -> post = array_merge($this -> post, $this -> _magicQuotes($name));
		}
	}
	public function getPost($name='',$default=null){
		return $name ? (isset($this -> post[$name]) ? $this -> post[$name] : $default) : $this -> post;
	}
	public function setGet($name,$value=''){
		if (!is_array($name)){
			$this -> get[$name] = $this -> _magicQuotes($value);
		}else{
			$this -> get = array_merge($this -> get, $this -> _magicQuotes($name));
		}
	}
	public function getGet($name='',$default=null){
		return $name ? (isset($this -> get[$name]) ? $this -> get[$name] : $default) : $this -> get;
	}
	public function isPost(){
		return $_SERVER['REQUEST_METHOD'] == 'POST';
	}
	public function frontUrl(){
		return isset($_SERVER["HTTP_REFERER"]) ? $_SERVER["HTTP_REFERER"] : '';
	}
	public function currentUrl(){
		$http = isset($_SERVER["HTTPS"])&&$_SERVER["HTTPS"] ? 'https' : 'http';
		$http .= '://';
		return $http.$_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	}
	public function requestUri(){
		if (isset($_SERVER['HTTP_X_REWRITE_URL'])){
			$uri = $_SERVER['HTTP_X_REWRITE_URL'];
		}elseif (isset($_SERVER['REQUEST_URI'])){
			$uri = $_SERVER['REQUEST_URI'];
		}elseif (isset($_SERVER['ORIG_PATH_INFO'])){
			$uri = $_SERVER['ORIG_PATH_INFO'];
			if (! empty($_SERVER['QUERY_STRING'])){
				$uri .= '?' . $_SERVER['QUERY_STRING'];
			}
		}else{
			$uri = '';
		}
		return $uri;
	}
	public function serverPort(){
		static $server_port = null;
		if ($server_port) return $server_port;
		if (isset($_SERVER['SERVER_PORT'])){
			$server_port = intval($_SERVER['SERVER_PORT']);
		}else{
			$server_port = 80;
		}

		if (isset($_SERVER['HTTP_HOST'])){
			$arr = explode(':', $_SERVER['HTTP_HOST']);
			$count = count($arr);
			if ($count > 1){
				$port = intval($arr[$count - 1]);
				if ($port != $server_port){
					$server_port = $port;
				}
			}
		}
		return $server_port;
	}
	public function isAjax(){
		return strtolower($this->header('X_REQUESTED_WITH')) == 'xmlhttprequest';
	}
	public function isFlash(){
		return strtolower($this->header('USER_AGENT')) == 'shockwave flash';
	}
	public function rawBody(){
		$body = file_get_contents('php://input');
		return (strlen(trim($body)) > 0) ? $body : false;
	}
	public function header($header){
		$temp = 'HTTP_' . strtoupper(str_replace('-', '_', $header));
		if (!empty($_SERVER[$temp])) return $_SERVER[$temp];
		if (function_exists('apache_request_headers')){
			$headers = apache_request_headers();
			if (!empty($headers[$header])) return $headers[$header];
		}
		return false;
	}
	public function getClientIp(){
		if (getenv("HTTP_CLIENT_IP") && strcasecmp(getenv("HTTP_CLIENT_IP"), "unknown"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR") && strcasecmp(getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR") && strcasecmp(getenv("REMOTE_ADDR"), "unknown"))
			$ip = getenv("REMOTE_ADDR");
		else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
			$ip = $_SERVER['REMOTE_ADDR'];
		else
			$ip = "unknown";
		return($ip);
	}
	public function parseQueryString($query_string){
		$a = explode('&', $query_string);
		$i = 0;$r = array();
		while ($i < count($a)) {
			$b = explode('=', $a[$i]);
			if(count($b) === 2)$r[urldecode($b[0])] = urldecode($b[1]);
			$i++;
		}
		return $r;		
	}
//--------------------------------------------------------------------------------	
	private function _magicQuotes($value){
		if (get_magic_quotes_gpc()){
			$value = is_array($value) ? array_map(array($this,__FUNCTION__), $value) : stripslashes($value);
		}
		return $value;
	}
	private function _addMagicQuotes($value){
		if (get_magic_quotes_gpc()){
			$value = is_array($value) ? array_map(array($this,__FUNCTION__), $value) : addslashes($value);
		}
		return $value;
	}	
}