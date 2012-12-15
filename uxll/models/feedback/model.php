<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: model.php
 */
require_once(MODELROOT."main/model.php");

class feedbackModel extends mainModel{

	private $error = 0;
	public function getLastError(){
		return $this -> error;
	}	
	public function append($msg) {
		$fbhelper = new feedbackHelper();
		$af = $fbhelper -> append($msg,"feedback");
		$rf = $this -> getSettingData();
		$ef = false;
		$content = $msg -> getData();
		if($rf['allowautosendemailtoremind'] === 'on'){
			require_once(LIBROOT."libraries/email.php");
			$config = require(CONFIGROOT."email.php");
			$email = new Email;
			$email -> smtp(
				'smtp.qq.com',
				25,
				$config['username'],
				$config['password'],
				$rf['remindemail'],
				'uxll.php.lib留言邮件提醒',
				WEB.'LQ?key=uxllphplibapikeyatshex'
				//$content['msg']
				//$this -> prettyHTML($msg -> getData())
			);
			$ef = $email -> send();	
//			if($ef)die("发送成功");			
		}
		return $af;
	}
	public function getFeedbackByLabel($label) {

	}
	public function getSettingData() {
		$ret = array();
		$ret['remindemail'] = $this -> getTemplateVar('remindemail');
		$ret['allowautosendemailtoremind'] = $this -> getTemplateVar('allowautosendemailtoremind');
		return $ret;
	}
	public function getTemplateVar($t_var,$sys = 'sys') {
		if(file_exists(ROOT."dc/cache/".$t_var.".preference.php")){
			$ori = require(ROOT."dc/cache/".$t_var.".preference.php");
			return $ori;
		}else{
			$rs = $this -> table("preference")
			-> fields("c") -> where("`k` = '".$t_var."' and `f` = '".$sys."'")
			-> select() -> all();
			
			if(is_array($rs) && count($rs) === 1){
				uxlldiycache::cache($rs[0]['c'],$t_var.".preference");
				return $rs[0]['c'];
			}
			return false;
		}
		
	}		
}