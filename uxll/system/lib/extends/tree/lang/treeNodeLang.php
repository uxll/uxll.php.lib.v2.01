<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-18 
 *  @author: uxll@qq.com
 *  @file: cn.php
 */
class treeNodeLang extends lang{
	private $lang = array();
	public function __construct(){
		$this->lang["keyinvalid"]='节点KEY不合法';
		$this->lang["childnodenotexist"]='孩子节点[{$KEY}]不存在';
		$this->lang["datanodenotexist"]='数据节点上不存在名为:[{$KEY}]的数据';
		$this->lang["nodeinvalid"]='当前节点还不是一个合法的结点';
		$this->lang["nodeexisted"]='节点[{$key}]已存在了';
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
