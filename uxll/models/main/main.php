<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: main.php
 */
class main extends CController{
	private $model;
	private $view;

	public function __construct(){
		require_once(COMMONROOT.'uxll.diy.cache.php');
		require_once(COMMONROOT.'divide.php');
		require_once(MODELROOT.'main/view.php');
		require_once(MODELROOT.'main/model.php');
		
		require_once(COMMONROOT.'mobileData.php');
		require_once(COMMONROOT.'mobileUI.php');
		
		require_once(MODELROOT.'main/common/ui.php');
		
		$this -> model = new mainModel();
		$this -> view = new mainView(
			$this -> model -> getChannel(),
			$this -> model -> getTemplateVars()
		);
		
	}
	public function welcome($msg){
		$title = $this -> model -> getTitle('/');
		$keywords = $this -> model -> getKeywords('/');
		$descriptions = $this -> model -> getDescriptions('/');
		$this -> view -> welcome($msg,$title,$keywords,$descriptions);
	}
	public function uxllautocall(CMessage $msg){
		$config = $this -> model -> getTemplateVars();
		if(isset($msg[':key'])){
			$tkd = $this -> model -> getArticalTKD($msg);
			$this -> view -> artical(
				$msg,
				$this -> model -> artical($msg),
				$this -> model -> comment($msg),
				$this -> model -> relativeArtical($msg),
				$tkd['title'],$tkd['keywords'],$tkd['description']
			);
		}else{
			$mode = $this -> model -> getDisplayMode($msg);
			if($mode === 'list'){
#var_dump($this -> model -> showlist($msg,10));
				$tkd = $this -> model -> getListTKD($msg);
				$this -> view -> showlist(
					$msg,$this -> model -> showlist($msg,$config['listnumofpage']),
					$tkd['title'],$tkd['keywords'],$tkd['description']
				);				
			}else if(is_array($mode)){
				if(array_key_exists('action',$mode)){
					$msg[':key'] = $mode['action'];
					return $this -> uxllautocall($msg);					
				}else if (array_key_exists('template',$mode)) {
					$msg[':template'] = $mode['template'];
					$tkd = $this -> model -> getListTKD($msg);
					$this -> view -> showlist(
						$msg,$this -> model -> showlist($msg,$config['listnumofpage']),
						$tkd['title'],$tkd['keywords'],$tkd['description']
					);	
				}

			}

		}
		
	}

	
//------------------------------------------------------

}