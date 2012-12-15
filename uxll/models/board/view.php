<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: view.php
 */
require_once(MODELROOT."main/view.php");
class boardView extends mainView{

	public function addBoard() {
		$feedback = new feedbackHelper();
		$ui = new mobileDisplayUI(
			"board",
			'',
			'<link rel="stylesheet" type="text/css" href="'.THEMEHOME.'ui/css/feedback.css">',
			"请您留言",
			"留言",
			"留言",
			'',
			''
		);
		$ui -> setBodyHtml(
			$this -> _get_board_add_ui($feedback)
		);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
	public function appendBoardSuccess() {
		$ui = new mobileDisplayUI(
			"channel",
			'',
			'',
			"留言成功",
			"留言",
			"留言",
			'
			<div style="text-align:center;font:normal 12px/25px arial;">
			<img src="'.COMMONUIHOME.'showMessage/successful/'.rand(1, 5).'.gif">操作成功...
			<br>
				<a href="/board" style="text-decoration:none">返回到上一页</a> | <a href="/" style="text-decoration:none">返回到首页</a>
			</div>	
			',
			''
		);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
	public function appendBoardFail() {
		$ui = new mobileDisplayUI(
			"channel",
			'',
			'',
			"留言失败",
			"留言",
			"留言",
			'
			<div style="text-align:center;font:normal 12px/25px arial;">
				<img src="'.COMMONUIHOME.'showMessage/_uploade_gexing_kuailedonggan_201081893443309.gif"><br>操作失败
				<br>
				<a href="/feedback" style="text-decoration:none">返回到上一页</a> | <a href="/" style="text-decoration:none">返回到首页</a>
			</div>
			',
			''
		);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
	public function display() {
		return $this -> addBoard();
	}
//--------------------------------------------------------------------------------------------------------------------------
	private function _get_board_add_ui($feedback) {
		return $feedback -> add(
			array(
				"fields" => array(
					"name" => array(
						"type" => "varchar"
						,"extraData" => array(
							"name" => "name"
							,"len" => 30
							,"defaultvalue" => ''
						)
						,"text" => "您的姓名"
					)
					,"age" => array(
						"type" => "varchar"
						,"extraData" => array(
							"name" => "age"
							,"len" => 2
							,"defaultvalue" => ''
						)
						,"text" => "您的年龄"
					)
					,"city" => array(
						"type" => "varchar"
						,"extraData" => array(
							"name" => "city"
							,"len" => 2
							,"defaultvalue" => ''
						)
						,"text" => "所在城市"
					)
					,"tel" => array(
						"type" => "varchar"
						,"extraData" => array(
							"name" => "tel"
							,"len" => 20
							,"defaultvalue" => '我们将为您回拨过去'
						)
						,"text" => "联系电话"
					)
					,"msg" => array(
						"type" => "text"
						,"extraData" => array(
							"cols" => "25"
							,"rows" => "3"
							,"name" => "msg"
							,"defaultvalue" => '请将您的疑问和想说的话告诉我们（如疾病症状，发病时间；投诉建议等）'
						)
						,"text" => "留言内容"
					)
				)
				,"caption" => "以下所填信息我们将为您保密"
				,"onsubmit" => "checkfeedback(this)"
				,"action" => "/board/append"
			)
			,THEMEROOT."template/board/add.tpl"
		);
	}
	private function _get_board_display_ui($feedback) {
		return $feedback -> display(
				array(
					"fields" => array(
						"name" => array(
							"text" => "您的姓名"
						)
						,"age" => array(
							"text" => "您的年龄"
						)
						,"city" => array(
							"text" => "所在城市"
						)
						,"tel" => array(
							"text" => "联系电话"
						)
						,"msg" => array(
							"text" => "留言内容"
						)
					)
				)
				,"board"
				,THEMEROOT."template/board/item.tpl"
			);
	}	
}