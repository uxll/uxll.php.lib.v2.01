<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: feedback.php
 */
class feedback extends CauthController{
	private $error='';
	private $login;
	public function getLastError(){
		return $this -> error;
	}
	public function __construct(){
		require_once(COMMONROOT.'uxll.diy.cache.php');
		require_once(MODELROOT.'feedback/view.php');
		require_once(MODELROOT.'feedback/model.php');
		require_once(MODELROOT.'main/common/ui.php');
		require_once(EXTROOT.'feedbackHelper/feedbackHelper.php');


		require_once(COMMONROOT.'mobileData.php');
		require_once(COMMONROOT.'mobileUI.php');
		require_once(COMMONROOT.'divide.php');		
		$this -> model = new feedbackModel();
		
		$this -> view = new feedbackView(
			$this -> model -> getChannel(),
			$this -> model -> getTemplateVars()
		);
		$this -> view -> init();
	}	
	public function checkPrivilege(CMessage $msg,CIdentityToken $it){
		return true;
	}	
	public function welcome($msg){
		$this -> view -> addFeedback();
	}
	public function append($msg) {
		$r = $this -> model -> append($msg);
		if($r){
			$this -> view -> appendFeedbackSuccess();
		}else{
			$this -> view -> appendFeedbackFail();
		}
	}
	public function display() {
		echo $this -> view ->  display();
	}
	public function add() {
		echo $this -> view ->  addFeedback();
	}
	public function readonly($msg){
		echo $this -> model -> getData();
	}
}