<?php
class tbhelperView extends CView{
	private $engine;
	private $error = 0;
	public function getLastError(){
		return $this -> error;
	}
	public function __construct(){
		$this -> engine = $this -> getEngine();
	}

	public function display($ui){
		return $this -> _html($ui,'display');	
	}

	public function add($ui){
		return $this -> _html($ui,'add');
	}
	public function edit($ui){
		return $this -> _html($ui,'edit');
	}
	public function view($ui){
		return $this -> _html($ui,'view');	
	}
//---------------------------------------------------------------------------------------------------------
	private function _html($ui,$name){
		ob_start();
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(UXLL_TBHELPER_ROOT.'template/'.$name.'.tpl');	
		$x = ob_get_contents();
		ob_end_clean();
		return $x;
			
	}	
}