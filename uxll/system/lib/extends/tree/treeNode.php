<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-18 
 *  @author: uxll@qq.com
 *  @file: treeNode.php
 */
define("TREENODEDATAACCESSPREFIX",":");
define("TREENODEKEYACCESSPREFIX","#");
class treeNode implements ArrayAccess{
	private $key = '';
	private $childnodes = array();
	private $data = array();
	private $isValid = false;
	private $lastError = '';
	static private $lang = null;
	public function __construct($key=""){
		require_once EXTROOT.'tree/lang/treeNodeLang.php';
		if(self::$lang === null){
			self::$lang = new treeNodeLang();
		}
		$this->setkey($key);
	}
	public function isValid(){
		return $this->isValid;
	}
	public function getkey(){
		return $this->key;
	}
	public function setkey($key){
		$v = new CKeyValidator();
		if($v->isValid($key)){
			$this->key=$key;
			$this->isValid=true;
			$this->lastError="";
		}else{
			$this->isValid=false;
			$this->lastError=self::$lang->show($key,array("key"=>$key));
			return false;
		}
		return true;
	}
	public function getLastError(){
		return $this->lastError;
	}
	public function addChild(treeNode $node){
		if($node->isValid()){
			$this->childnodes[$node->getkey()] = $node;
			return true;
		}
		$this->lastError=self::$lang->show('nodeinvalid');
		return false;
	}
	public function getChild($childkey){
		if($this->_childExist($childkey)){
			return $this->childnodes[$childkey];
		}
		$this->lastError=self::$lang->show('childnodenotexist',array('key'=>$childkey));
		return null;
	}
	public function removeChild($key){
		if($this->_childExist($key)){
			unset($this->childnodes[$key]);
			return true;
		}
		$this->lastError=self::$lang->show('childnodenotexist',array("key"=>$key));
		return false;
	}
	public function setData($key,$data){
		if($this->isValid()){
			$this->data[$key] = $data;
			return true;
		}
		$this->lastError=self::$lang->show('nodeinvalid');
		return false;
	}
	public function getData($name){
		if($this->_dataExist($name)){
			return $this->data[$name];
		}
		return null;
	}
	public function removeData($name){
		if($this->_dataExist($name)){
			unset($this->data[$key]);
			return true;
		}
		$this->lastError=self::$lang->show('datanodenotexist',array("name"=>$name));
		return false;
	}
	public function offsetExists($offset){
		if(!$this->isValid){
			$this->lastError=self::$lang->show('nodeinvalid');
			return false;
		}
		$mode = $this->_assertGetDataMode($offset);
		switch ($mode){
			case 'key':
				return true;
			case 'data':
				$name=substr($offset,1);
				return $this->_dataExist($name);
			case 'child':
				$key = $offset;
				return $this->_childExist($key);
		}
		return false;
	}
	public function offsetGet ($offset){
		if(!$this->isValid){
			$this->lastError=self::$lang->show('nodeinvalid');
			return false;
		}
		$mode = $this->_assertGetDataMode($offset);
		switch ($mode){
			case 'key':
				return $this->getkey();
			case 'data':
				$key=substr($offset,1);
				return $this->getData($key);
			case 'child':
				$key = $offset;
				return $this->getChild($key);
		}
		return null;
	}
	public function offsetSet ($offset, $value){
		if(!$this->isValid){
			$this->lastError=self::$lang->show('nodeinvalid');
			return false;
		}
		$mode = $this->_assertGetDataMode($offset);
		switch ($mode){
			case 'key':
				return $this->setkey($value);
			case 'data':
				$key=substr($offset,1);
				return $this->setData($key,$value);
			case 'child':
				if($value INSTANCEOF treeNode){
					return $this->addChild($value);
				}
				return false;
		}
		return false;
	}
	public function offsetUnset ($offset){
		if(!$this->isValid){
			$this->lastError=self::$lang->show('nodeinvalid');
			return false;
		}
		$mode = $this->_assertGetDataMode($offset);
		switch ($mode){
			case 'key':
				$this->key = '';
				$this->isValid = false;
				return true;
			case 'data':
				$key=substr($offset,1);
				return $this->removeData($key);
			case 'child':
				return $this->removeChild($offset);
		}
		return false;
	}
	private function _assertGetDataMode($offset){
		if(substr($offset,0,1) === TREENODEDATAACCESSPREFIX){
			return 'data';
		}elseif ($offset === TREENODEKEYACCESSPREFIX){
			return 'key';
		}
		return 'child';
	}
	private function _childExist($key){
		return array_key_exists($key, $this->childnodes);
	}
	private function _dataExist($name){
		return array_key_exists($name, $this->data);
	}
}