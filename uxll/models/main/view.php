<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: view.php
 */
class mainView extends CView{
	protected $engine;
	private $error = 0;
	private $channel ;
	private $templateVars ;
	public function getLastError(){
		return $this -> error;
	}
	
	public function __construct($channel,$templateVars){
		$this -> engine = $this -> getEngine();
		$this -> channel = $channel;
		$this -> templateVars = $templateVars;
		$this -> engine -> assign("tel",$templateVars["tel"]);
		foreach($templateVars as $key => $config){
			$this -> engine -> assign(
				$key,
				$this -> engine -> fetch("string:".$config)
			);	
		}
		
	}

	public function welcome($msg,$title,$keywords,$descriptions){
		$ui = new mobileDisplayUI(
			"home",
			'',
			'',
			$title,
			$keywords,
			$descriptions,
			'',
			''
		);
		$ui -> setChannel($this -> channel);
		$ui -> setTemplateVars($this -> templateVars);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
	
	public function showlist($msg,$rs,$title,$keywords,$description){
		$cc = $this -> channel;
		$title = $title==='' ? $cc[$msg->getAction()] : $title;

		$keywords = $keywords==='' ? $title : $keywords;
		$description = $description==='' ? $title : $description;
		$ui = new mobileDisplayUI(
			"channel",
			$msg -> getAction(),
			'',
			$title,
			$keywords,
			$description,
			'',
			''
		);
		$ui -> setChannel($this -> channel);
		$ui -> setTemplateVars($this -> templateVars);
		if(!$rs){
			$ui -> setBodyhtml("<div class='nonews'>当前还没有消息</div>");
		}else{
			$this -> engine -> assign('ui',$ui);
			if(isset($msg[':template'])){//have own template
				$ui -> setBodyhtml($this -> engine -> fetch('string:'.$msg[':template']))	
				;//die($bodyhtml);
			}else{
//die(P($rs['rs']));
				$this -> engine -> assign('rs',$rs['rs']);
				$this -> engine -> assign('html',$rs['pagehtml']);
				$this -> engine -> assign('control',$msg->getAction());
				$this -> engine -> assign('control_text',$this -> channel[$msg->getAction()]);					
				$bodyhtml = $this -> engine -> fetch(THEMEROOT.'template/list.htm');	
				$this -> engine -> clearAssign(array('rs', 'html', 'control','control_text'));	
				$ui -> setBodyhtml($bodyhtml);
			}
		}

		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}

	public function artical($msg,$rs,$comment,$relativeArtical,$title,$keywords,$description){
		$cc = $this -> channel;//$cc[$msg->getAction()]
		$title = $title=== '' ? (isset($rs['rs'][0]['title']) ? $rs['rs'][0]['title'] : $title): $title;
		$keywords = $keywords==='' ? $title : $keywords;
		$description = $description==='' ? $title : $description;
		$ui = new mobileDisplayUI(
			"artical",
			$msg -> getAction(),
			'',
			$title,
			$keywords,
			$description,
			'',
			''
		);
		if(!$rs){
			$ui -> setBodyhtml("当前还没有文章内容");
		}else{
			if(count($rs['rs'])==1){
#				die("goggo");
				$this -> engine -> assign('ui',$ui);
				$rs['rs'][0]['html'] = $this -> engine -> fetch('string:'.$rs['rs'][0]['html']);
			}

			$this -> engine -> assign('page',$msg[':page']);
			$this -> engine -> assign('rs',$rs['rs']);
			$this -> engine -> assign('html',$rs['pagehtml']);
			$this -> engine -> assign('comment',$comment);
			$this -> engine -> assign('relativeArtical',$relativeArtical);
			$this -> engine -> assign('control',$msg->getAction());
			$this -> engine -> assign('control_text',$this -> channel[$msg->getAction()]);
			$bodyhtml = $this -> engine -> fetch(THEMEROOT.'template/artical.htm')	;
			$ui -> setBodyhtml($bodyhtml);
			$this -> engine -> clearAssign(array('rs', 'html', 'control','control_text','page','comment'));	
		}
		$ui -> setChannel($this -> channel);
		$ui -> setTemplateVars($this -> templateVars);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(THEMEROOT.'template/skeleton.htm');
	}
//----------------------------------------------------------------------
	private function _g_index_ui($rs){
		//$theme = defined("CURRENTTHEME") ? CURRENTTHEME : "default";
		//$this -> engine -> assign('rs',$rs);
		//return $this -> engine -> fetch(ROOT.'model/'.$theme.'/template/index-list.htm');
	}	
	
}