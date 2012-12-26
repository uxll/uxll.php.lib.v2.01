<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-26 
 *  @author: uxll@qq.com
 *  @file: demoTree.php
 */
class demoTree extends CController{
	public function welcome($msg){
		require_once EXTROOT.'tree/tree.php';
		$rootnode = new treeNode();
		$rootnode->setkey("qq");
		$rootnode->setData('mm','i love mm');
		$rootnode["vv"] = new treeNode("vv");
		$rootnode["vv"]->setData('mm','i love mm');
		
		$tree = new tree;
		$tree->setRoot($rootnode);
		$test = $tree->getNode("/vv/");
		var_dump($test);
		var_dump($tree->getLastError());
	}
}