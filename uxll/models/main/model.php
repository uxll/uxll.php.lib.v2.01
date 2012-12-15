<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: model.php
 */
class mainModel extends CModel{

	private $error = 0;
	private $engine;
	public function getLastError(){
		return $this -> error;
	}	
	public function getChannel() {
		$channel = R('CHANNEL');
		if($channel){
			return R('CHANNEL');	
		}else{
			require_once(COMMONROOT."channel.php");
			$channel = new channelData();
			return $channel -> getChannel();	
		}		
	}
	public function getTemplateVars() {
		if(file_exists(ROOT."dc/cache/preference.php")){
			$ori = require(ROOT."dc/cache/preference.php");
		}else{
			$ori = $this -> table("preference") -> fields("k,c")
			-> select() -> all()
			;
			uxlldiycache::cache($ori,"preference");
		}
		$ret = array();
		
		foreach($ori as $val){
			$ret[$val["k"]] = $val["c"];
		}
		return $ret;		
	}
	public function getHomepageContent(){
		if(file_exists(ROOT."dc/cache/HomepageContent.php")){
			$rs = require_once(ROOT."dc/cache/HomepageContent.php");	
		}else{
			$rs = $this -> table('channel')	-> order('`id` asc') -> select() -> all();
			uxlldiycache::cache($rs,"HomepageContent");
		}
		return $rs;
	}
	public function getTitle($path){
		if(file_exists(ROOT."dc/cache/Title".md5($path).".php")){
			$rs = require_once(ROOT."dc/cache/Title".md5($path).".php");	
		}else{
			$rs = $this -> table('keywords') -> fields('`title`') -> where("`path`='".$path."'") -> select() -> all();
			uxlldiycache::cache($rs,"Title".md5($path));
		}
		if($rs){
			return $rs[0]['title'];
		}
		return '';
	}
	public function getKeywords($path){
		if(file_exists(ROOT."dc/cache/Keywords".md5($path).".php")){
			$rs = require_once(ROOT."dc/cache/Keywords".md5($path).".php");	
		}else{
			$rs = $this -> table('keywords') -> fields('`keywords`') -> where("`path`='".$path."'") -> select() -> all();
			uxlldiycache::cache($rs,"Keywords".md5($path));
		}
		if($rs){
			return $rs[0]['keywords'];
		}
		return '';
	}
	public function getDescriptions($path){
		if(file_exists(ROOT."dc/cache/Descriptions".md5($path).".php")){
			$rs = require_once(ROOT."dc/cache/Descriptions".md5($path).".php");	
		}else{
			$rs = $this -> table('keywords') -> fields('`description`') -> where("`path`='".$path."'") -> select() -> all();
			uxlldiycache::cache($rs,"Descriptions".md5($path));
		}
		if($rs){
			return $rs[0]['description'];
		}
		return '';
	}
	public function comment($msg){
		if(!isset($msg[':key'])){
			return array();	
		}
		$path = '/'.$msg -> getAction().'/'.$msg[':key'];
		if(file_exists(ROOT."dc/cache/comment".md5($path).".php")){
			$rs = require_once(ROOT."dc/cache/comment".md5($path).".php");	
		}else{
			$rs = $this -> table('comment') -> fields('`content`') -> where("`location`='".$path ."'") -> limit('0,5') -> select() -> all();	
			uxlldiycache::cache($rs,"comment".md5($path));
		}
		return $rs;
	}
	public function relativeArtical($msg){
		$path = $msg->getAction();
		if(file_exists(ROOT."dc/cache/relativeArtical".md5($path).".php")){
			$noid = require_once(ROOT."dc/cache/relativeArtical".md5($path).".php");	
		}else{
			$rand = $this -> table('artical') -> fields('`title`,`urlkey`') -> order('RAND()') -> limit('0,4') -> where("`channel`='".($msg->getAction()) ."'") -> select() -> all();
			$noid = array(
				'',
				'',
				$rand
			);
			$id = $this -> table('artical') -> fields('`id`') -> where("`channel`='".($msg->getAction()) ."' and `urlkey` = '".$msg[':key']."'") -> limit('0,1') -> select() -> all();
			if(is_array($id) && count($id) == 1){
				$prev_rs = $this -> table('artical') -> fields('`title`,`urlkey`') -> where("`id`>".($id[0]['id']) ." and `channel`='".($msg->getAction()) ."'") -> limit('0,1') -> select() -> all();
				if(is_array($prev_rs) && count($prev_rs) == 1){
					$noid[0] = $prev_rs;
				}
			}
			if(is_array($id) && count($id) == 1){
				$next_rs = $this -> table('artical') -> fields('`title`,`urlkey`') -> where("`id`<".($id[0]['id']) ." and `channel`='".($msg->getAction()) ."'") -> limit('0,1') -> select() -> all();
				if(is_array($next_rs) && count($next_rs) == 1){
					$noid[1] = $next_rs;
				}
			}
			uxlldiycache::cache($noid,"relativeArtical".md5($path));
		}
		
		return $noid;
	}
	public function showlist($msg,$listnumofpage){
		$path = $msg->getAction();
		if(file_exists(ROOT."dc/cache/list".md5($path).".php")){
			$rs = require_once(ROOT."dc/cache/list".md5($path).".php");	
		}else{
			$r = $this -> table('artical') -> fields('`title`,`urlkey`') 
			-> order('`order` asc,`id` desc') 
			-> where("`channel`='".($msg->getAction()) ."'") 
			-> page(5,$listnumofpage);
			$rs = array(
				'pagehtml' => $r -> getPageHTML(),
				'rs' => $r -> select() -> all()
				
			);
			uxlldiycache::cache($rs,"list".md5($path));
		}
		return $rs;
	}
	public function artical($msg){
		$path = $msg->getAction().$msg[':key'];
		if(file_exists(ROOT."dc/cache/artical".md5($path).".php")){
			$rs = require_once(ROOT."dc/cache/artical".md5($path).".php");	
		}else{
			$r = $this -> table('artical') -> fields('`title`,`urlkey`,`time`,`html`,`nocomment`,`norelative`') -> where("`channel`='".($msg->getAction()) ."' and `urlkey`='".$msg[':key']."'");	
			$rs = array(
				'pagehtml' => '',
				'rs' => $r -> select() -> all()
			);
			uxlldiycache::cache($rs,"artical".md5($path));
		}
		return $rs;
	}	
	public function getArticalTKD($msg){

		if(!isset($msg[':key']))return array(
			'title'=>'',
			'keywords'=>'',
			'description'=>'',			
		);
		$path = '/'.$msg -> getAction().'/'.$msg[':key'];
		if(file_exists(ROOT."dc/cache/ArticalTKD".md5($path).".php")){
			$rs = require_once(ROOT."dc/cache/ArticalTKD".md5($path).".php");	
		}else{
			
			$rs = $this -> table('keywords') -> where("`path`='".$path."'") -> select() -> all();
			if($rs){
				uxlldiycache::cache($rs[0],"ArticalTKD".md5($path));
				return $rs[0];
			}
			return array(
				'title'=>'',
				'keywords'=>'',
				'description'=>'',			
			);
		}
		return $rs;
	}
	public function getListTKD($msg){
		$path = '/'.$msg -> getAction();
		if(file_exists(ROOT."dc/cache/ListTKD".md5($path).".php")){
			return require_once(ROOT."dc/cache/ListTKD".md5($path).".php");	
		}else{
			$rs = $this -> table('keywords') -> where("`path`='".$path."'") -> select() -> all();
			if($rs){
				uxlldiycache::cache($rs[0],"ListTKD".md5($path));
				return $rs[0];
			}
			return array(
				'title'=>'',
				'keywords'=>'',
				'description'=>'',			
			);
		}
	}
	public function getDisplayMode($msg){
		$path = $msg -> getAction();
		if(file_exists(ROOT."dc/cache/DisplayMode".md5($path).".php")){
			return require_once(ROOT."dc/cache/DisplayMode".md5($path).".php");	
		}else{
			$r = $this -> table('channel') -> fields('template')
			-> where("`link`='".($msg->getAction()) ."'")
			-> select() -> all();
			if(count($r) === 1){
				if($r[0]['template']){
					$ret = array(
						'template' => $r[0]['template']
					);	
					uxlldiycache::cache($ret,"DisplayMode".md5($path));
					return $ret;
				}
			}else{
				$ret = "list";
				uxlldiycache::cache($ret,"DisplayMode".md5($path));
				return $ret;
			}
			
			//如果频道下只有一个文章，就直接显示文章，不用列表了
			$r = $this -> table('artical') -> fields('`urlkey`') 
			-> order('`order` asc,`id` desc') 
			-> where("`channel`='".($msg->getAction()) ."'") 
			-> limit('0,2') -> select() -> all();
			if(count($r)===2){
				//说明不是只有一篇文章
				$rs = $this -> table('channel') -> fields('`viewmode`')
				-> where("`link`='".$msg -> getAction()."'")
				-> select() -> all();
				if(count($rs)===1){
	//				var_dump($rs[0]['viewmode']);
					if($rs[0]['viewmode']==='no'){
						$ret = "list";
						uxlldiycache::cache($ret,"DisplayMode".md5($path));
						return $ret;
					}else{
						$ret = array(
							'action' => $r[0]['urlkey']	
						);	
						uxlldiycache::cache($ret,"DisplayMode".md5($path));
						return $ret;
					}
				}
				$ret = "list";
				uxlldiycache::cache($ret,"DisplayMode".md5($path));
				return $ret;
			}else if(count($r)===1){
				$ret = array(
					'action' => $r[0]['urlkey']
				);
				uxlldiycache::cache($ret,"DisplayMode".md5($path));
				return $ret;
			}else{
				$ret = "list";
				uxlldiycache::cache($ret,"DisplayMode".md5($path));
				return $ret;
			}				
		}
	}	
}