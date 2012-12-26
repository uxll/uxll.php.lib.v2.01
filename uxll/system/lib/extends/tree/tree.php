<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-15 
 *  @author: uxll@qq.com
 *  @file: tree.php
 */
require_once EXTROOT.'tree/treeNode.php';
class tree{
	private $root = null;
	private $isValid = false;
	private $lastError = '';
	static private $lang = null;
	private $error;
	
	public function __construct(treeNode $root=null){
		require_once EXTROOT.'tree/lang/treelang.php';
		if(self::$lang === null){
			self::$lang = new treeLang();
		}
		if(!is_null($root)){
			$this->setRoot($root);
		}
	}
	public function getLastError(){
		return $this->error;
	}
	public function setRoot(treeNode $root){
		if($root->isValid($root)){
			$this->root=$root;
			$this->isValid=true;
		}else{
			$this->isValid=false;
			$this->lastError=self::$lang->show('keyinvalid');
		}
	}
	public function getRoot(){
		if(!$this->isValid){
			$this->lastError=self::$lang->show('treeinvalid');
			return null;
		}
		if(is_null($this->root)){
			return null;
		}
		return $this->root;
	}
	public function getNode($tree_path){
		if(!$this->isValid){
			$this->lastError=self::$lang->show('treeinvalid');
			return null;
		}
		$v = new CTreeKeyPathValidator;
		if($v->isValid($tree_path)){
			$p = $this->_parseTreePath($tree_path);
			$t = $this->getRoot();
			foreach ($p as $v){
				$t = $t[$v];
				if(!($t instanceof treeNode)){
					$this->lastError=self::$lang->show('treepathnotexist',array('p'=>$tree_path));
					return null;
				}
			}
			return $t;
		}
		$this->error=self::$lang->show('invalidtreepath',array("p"=>$tree_path));
		return null;
	}
	
	public function duplicate($destination_path,tree $tree){
		if(!$tree->isValid()){
			$this->error=self::$lang->show("treeinvalid");
			return false;
		}
		$destination_node = $this->getNode($destination_path);
		if(!($destination_node instanceof treeNode)){
			$this->error=self::$lang->show("treepathnotexist",array("p"=>$destination_path));
			return false;
		}
		$destination_node->addChild($tree->getRoot());		
	} 
	public function getParentPath($tree_path){
		
	}
	private function _parseTreePath($tree_path){
		$d = explode("/", $tree_path);
		array_shift($d);
		array_pop($d);
		return $d;
	}
}