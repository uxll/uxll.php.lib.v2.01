<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: httppost.php
 */
function httppost($url,$callback,$data = array()){
	$postdata="";
	$bak=array();
	foreach($data as $i => $v){
		$postdata.= $i . "=" . urlencode($v) . "&";
		$bak[$i]=$v;
	}
	$ch = curl_init();
	curl_setopt($ch,CURLOPT_SSL_VERIFYPEER,FALSE);
	curl_setopt($ch,CURLOPT_URL,$url);
	#curl_setopt($ch,CURLOPT_POST,1);
	curl_setopt($ch,CURLOPT_POSTFIELDS,$postdata);

	//Start ob to prevent curl_exec from displaying stuff.
	ob_start();
	curl_exec($ch);

	//Get contents of output buffer
	$info = ob_get_contents();
	curl_close($ch);

	//End ob and erase contents.
	ob_end_clean();
	return call_user_func_array($callback,array($info));
}