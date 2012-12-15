<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: view.php
 */
require_once(MODELROOT."main/view.php");
class feedbackView extends mainView{


	private $doctor;
	private $disease;
	private $result;
	private $attutide;
	public function init(){
		require_once(THEMEROOT."template/feedback/config.php");
		$this -> doctor = $doctor;
		$this -> disease = $disease;
		$this -> result = $result;
		$this -> attutide = $attutide;	
	}
	public function addFeedback() {
		$feedback = new feedbackHelper();
		$this -> engine -> assign('doctor',$this -> doctor);
		$this -> engine -> assign('disease',$this -> disease);
		$this -> engine -> assign('result',$this -> result);
		$this -> engine -> assign('attutide',$this -> attutide);
		$ui = new mobileDisplayUI(
			"feedback",
			'',
			'<link rel="stylesheet" type="text/css" href="'.THEMEHOME.'ui/css/feedback.css">',
			"患者点评 ",
			"留言",
			"留言",
			'',
			''
		);
		
		$ui -> setBodyHtml(
			$this -> engine -> fetch(THEMEROOT."template/feedback/add.tpl")
		);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
	public function appendFeedbackSuccess() {
		$ui = new mobileDisplayUI(
			"channel",
			'',
			'',
			"留言成功",
			"留言",
			"留言",
			'
			<div style="text-align:center;font:normal 12px/25px arial;">
			<img src="'.SYSUIHOME.'showMessage/successful/'.rand(1, 5).'.gif"><br>留言成功,需要审核才能显示
			<br>
				<a href="/feedback" style="text-decoration:none">返回到留言面</a> | <a href="/" style="text-decoration:none">返回到首页</a>
			</div>	
				
			',
			''
		);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
	public function appendFeedbackFail() {
		$ui = new mobileDisplayUI(
			"channel",
			'',
			'',
			"留言失败",
			"留言",
			"留言",
			'
			<div style="text-align:center;font:normal 12px/25px arial;">
				<img src="'.SYSUIHOME.'showMessage/_uploade_gexing_kuailedonggan_201081893443309.gif"><br>留言失败
				<br>
				<a href="/feedback" style="text-decoration:none">返回到留言面</a> | <a href="/" style="text-decoration:none">返回到首页</a>
			</div>
			
			',
			''
		);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
	public function display() {
		$feedback = new feedbackHelper();
		$ui = new mobileDisplayUI(
			"feedback",
			'',
			'
			<link rel="stylesheet" type="text/css" href="'.THEMEHOME.'ui/css/feedback.css"> 
			',
			"请您留言",
			"留言",
			"留言",
			'',
			''
		);
		$ui -> setBodyHtml(
			"<div class='uxll-feedback'>".
			"<p align='right'>".
			"<a href='/feedback/add'>".
			"<img src='".THEMEHOME."template/feedback/img/xxwdkbd_136px34.jpg'>".
			"</a>".
			"</p>".
			$this -> _get_feedback_display_ui($feedback)
			.
			"</div>"
		);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
		
	}
//--------------------------------------------------------------------------------------------------------------------------
	private function _get_feedback_display_ui($feedback) {
		return $feedback -> display(
				array(
					"fields" => array(
						"name" => array(
							"text" => "您的姓名"
						)
						,"disease" => array(
							"text" => "所患疾病"
							,"value" => ($this -> disease)
						)
						,"doctor" => array(
							"text" => "主治医生"
							,"value" => ($this -> doctor)
						)
						,"result" => array(
							"text" => "疗效"
							,"value" => ($this -> result)
						)
						,"attutide" => array(
							"text" => "态度"
							,"value" => ($this -> attutide)
						)
						,"msg" => array(
							"text" => "看病过程"
						)
					)
				)
				,"feedback"
				,THEMEROOT."template/feedback/item.tpl"
			);
	}	
}