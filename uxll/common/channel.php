<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: channel.php
 */
class channelData extends CModel{
	public function getChannel() {
		$channel = R('CHANNEL');
		if($channel){
			return R('CHANNEL');	
		}
		if(file_exists(ROOT.'dc/cache/channel.php')){
			R('CHANNEL',require_once(ROOT.'dc/cache/channel.php'));
			return R('CHANNEL');
		}
		$ori = $this  -> table("channel") -> fields("text,link")
		-> select() -> all()
		;
		$ret = array();
		foreach($ori as $val){
			$ret[$val["link"]] = $val["text"];
		}

		uxlldiycache::cache($ret,'channel');
		R('CHANNEL',$ret);
		return $ret;		
	}	
}
