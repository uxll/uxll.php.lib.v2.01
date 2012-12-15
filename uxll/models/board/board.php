<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: board.php
 */
class board extends CauthController{
	private $error='';
	private $login;
	public function getLastError(){
		return $this -> error;
	}
	public function __construct(){
		require_once(COMMONROOT.'uxll.diy.cache.php');
		require_once(MODELROOT.'board/view.php');
		require_once(MODELROOT.'board/model.php');
		require_once(MODELROOT.'main/common/ui.php');
		require_once(EXTROOT.'feedbackHelper/feedbackHelper.php');
		require_once(COMMONROOT.'mobileData.php');
		require_once(COMMONROOT.'mobileUI.php');
		require_once(COMMONROOT.'divide.php');
		$this -> model = new BoardModel();

		$this -> view = new BoardView(
			$this -> model -> getChannel(),
			$this -> model -> getTemplateVars()
		);
	}

	public function checkPrivilege(CMessage $msg,CIdentityToken $it){
		return true;
	}

	public function welcome($msg){
		$this -> view -> display();
	}
	public function append($msg) {
		$r = $this -> model -> append($msg);
		if($r){
			$this -> view -> appendBoardSuccess();
		}else{
			$this -> view -> appendBoardFail();
		}
	}

	public function display() {
		echo $this -> view ->  display();
	}
	public function add() {
		echo $this -> view ->  addBoard();
	}
}