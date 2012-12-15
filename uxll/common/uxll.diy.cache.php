<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: uxll.diy.cache.php
 */
class uxlldiycache{
	static public function duplicate($var){
		return $ret;
	}
	static public function debug($var,$format,$deep = 1,$mode = false){
		$temp = array();$ret = array();
		switch(gettype($var)){
			
			case 'string':
			case 'number':
				return "'".($format ? preg_replace("/\r\n|\t/",'',str_replace("'",'\\\'',$var)) : str_replace("'",'\\\'',$var))."'";
			case 'array':
				$ret[] = 'array(';
				foreach($var as $x => $v){
					$temp[] = ($format ? "\r\n" . str_repeat("\t",$deep) : '').
						'"'. addslashes($x) . '" => '.
						uxlldiycache::debug($v,$format,$deep+1,$mode)
					;
				}
				$ret[] = join(",",$temp);
				if($format)$ret[] = ($format ? "\r\n" : '').str_repeat("\t",$deep-1);
				$ret[] =')';
				break;
			default:
				return $var;
		}
			

		$ret = join("",$ret);
		return $ret;
	
	}
	static public function stringify($var){
		return uxlldiycache::debug($var,true,1,false);
	}
	static public function cache($data,$name){
		if(UXLL_DEBUG_FLAG)return false;
		$ret_str = uxlldiycache::stringify($data);
		CCache::update(ROOT.'dc/cache/',$name.'.php','<?php
		return '.$ret_str.';'
		);	
	}
}