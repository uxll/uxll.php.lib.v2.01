<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-19 
 *  @author: uxll@qq.com
 *  @file: treelang.php
 */
class treeLang extends lang{
	private $lang = array();
	public function __construct(){
		$this->lang["keyinvalid"]='节点KEY不合法';
		$this->lang["invalidtreepath"]='树路径不合法:{$p}';
		$this->lang["treeinvalid"]='当前的树还是无效的';
		$this->lang["treepathnotexist"]='路径[{$p}]在当前树中不存在';
	}
	public function show($key,$replacement=array()){
		if(key_exists($key, $this->lang)){
			return $this->l($this->lang[$key],$replacement);
		}
		return $this->l($this->e($key),$replacement) ;
	}

	private function e($key){
		return 'KEY[{$key}]不在语言节点中';
	}
}
