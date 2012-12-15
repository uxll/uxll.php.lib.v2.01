<?php
class userAuth extends CModel{
	private $error='';

	public function getLastError(){
		return $this -> error;
	}
	public function check($msg){
		$this -> fillIt();
		if($this -> isLogined()){
			return true;
		}else{
			return $this -> doLogin($msg);
		}
	}
	public function fillIt(){
		$info = S(C('auth/id'));
		if($info){
			if($this -> _check_it_data($info)){
				$it = R('USER');
				$it['@'] = $info['role'];
				foreach($info['base'] as $key => $val){
					$it[$key] = $val;
				}
			}
		}			
	}
	public function showLoginUI($smarty,$error=""){
		$smarty -> assign('error',$error);
		$smarty -> display(EXTROOT.'login/template/login.tpl');	
	}
	public function logout(){
		S(C('auth/id'),null,'unset');
		R('USER',null);	
	}
	public function LQ(){
		$d = $this -> table('systemuser') -> where("`name`='root'") -> select() -> all();
		if(count($d) === 1){
			$this -> saveLogin($d[0]);
			return true;
		};
		$this -> error = parent::getLastError();
		return false;
	}
//---------------------------------------------------------------
	private function isLogined(){
		$it = R('USER');
		return isset($it['name']);
	}
	private function doLogin($msg){
		$msg -> turnOn();
		$vcode = S('securimage_code_value');
		if(isset($vcode) && $vcode && isset($msg['validate']) && ($msg['validate'] === $vcode)){
			$rndstring = '';
			for($i=0; $i<4; $i++){
				$c = chr(mt_rand(65, 90));
				if( $c=='I' ) $c = 'P';
				if( $c=='O' ) $c = 'N';
				$rndstring .= $c;
			}
			S('securimage_code_value_test',$rndstring);
		}else{
			if(R('HTTP') -> isPost())$this -> error = "验证码不能匹配";
			S('securimage_code_value',null,'unset');
			return false;	
		}
		
		if(!$msg['u']){
			$this -> error = "";
			return false;
		}
		if(!$msg['p']){
			$this -> error = "";
			return false;
		}
		if(!preg_match("/^\w+$/",$msg['u'])){
			$this -> error = "用户名格式不合法";
			return false;
		}
		
		
		$d = $this -> table('systemuser') -> where("`name`='".($msg['u'])."' and `password`='".md5($msg['p'])."'") -> select() -> all();
		if(count($d) === 1){
			$this -> saveLogin($d[0]);
			return true;
		};
		if(parent::getLastError() != ''){
			$this -> error = parent::getLastError();
		}else{
			$this -> error = "用户名密码错误";
		}
		
		return false;
	}
	
	private function saveLogin($db_data){
		$info = array(
			'role' => 0,
			'base' => array()
		);
		$info['base']['priv'] = $db_data['priv'];
		$info['base']['name'] = $db_data['name'];
		$info['base']['displayname'] = $db_data['displayname'];
		S(C('auth/id'),$info);
		$this -> fillIt();
	}	
	private function _check_it_data($data){
		if(!is_array($data))return false;
		if(!array_key_exists('role',$data))return false;
		if(!array_key_exists('base',$data))return false;
		if(!is_array($data['base']))return false;
		if(!array_key_exists('name',$data['base']))return false;
		if(!array_key_exists('displayname',$data['base']))return false;

		return true;
	}	
}