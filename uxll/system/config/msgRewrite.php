<?php
class msgRewrite extends CModel implements IMessageRewrite {
	private $isValid = false;
 	public function rewrite(CMessage $msg){
		if(!class_exists('uxlldiycache')){
 			require_once(ROOT.'uxll/common/uxll.diy.cache.php');
 		}
 		if(!class_exists('mobileData')){
 			require_once(ROOT."uxll/common/channel.php");
 		}
 		$channelData = new channelData();
		$list_artical = $channelData -> getChannel();	
		if(array_key_exists($msg -> getControl(),$list_artical)){
			if($msg -> is_default_action()){
				$msg -> build(array(
					'control' => 'main',
					'action' => $msg -> getControl()
				));
				require_once(ROOT.'model/main/main.php');
				$this -> isValid = true;
				return $msg;
			}else{
				$rs = $this -> table('artical') -> fields('urlkey,title') -> where("`channel`= '".$msg -> getControl()."' and `urlkey`= '".$msg -> getAction()."'") -> select() -> all();
				if($rs && is_array($rs) && count($rs)==1 && $msg -> getAction()===$rs[0]['urlkey']){
					$msg -> build(array(
						'control' => 'main',
						'action' => $msg -> getControl(),
						'arguments' => array(
							'key' => $msg -> getAction()
						)
					));
					require_once(ROOT.'model/main/main.php');
					$this -> isValid = true;
					return $msg;
				}				
			}

		}else{
			$this -> isValid = false;	
		}
		return $msg;
	}
	public function isValid(){
		return $this -> isValid;	
	}
}