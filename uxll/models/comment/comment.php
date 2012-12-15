<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: comment.php
 */
class comment extends CController{
	private $model;
	private $view;

	public function __construct(){
		require_once(MODELROOT.'comment/view.php');
		require_once(MODELROOT.'comment/model.php');

		$this -> view = new commentView();
		$this -> model = new commentModel();
	}

	public function welcome($msg){
		$r = $this -> model -> addcomment($msg);
		if($r){
			$this -> view -> goback();	
		}else{
			$this -> view -> fail('','暂时不可以评论');	
		}
	}


	
//------------------------------------------------------

}