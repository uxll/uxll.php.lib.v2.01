<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: userFunctions.php
 */
class channelconfig implements IuserFunction{
	public function execute($row,$action,$text){
		if(method_exists($this,$action)) return $this -> $action($text,$row);
		return "no method:".$action;
	}	
	public function viewmode($text,$row){
		return $row['viewmode'] === 'yes' ? '显示第一篇文章' : '列表显示';	
	}
	public function removechannel($text,$row){
		return "<a onclick='return confirm(\"真的要删除?\")' href='/privilege/removechannel?pk=".$row['id']."'>".$text."</a>";	
	}
	public function editchannel($text,$row){
		return "<a href='/privilege/editchannel?pk=".$row['id']."'>".$text."</a>";	
	}
	public function viewchannelartical($text,$row) {//link
		return "<a href='/privilege/searchartical?q=".urlencode("sql:`channel`='".$row['link']."'")."'>".$row['link']."</a>";	
	}
}

class articalconfig implements IuserFunction{
	public function execute($row,$action,$text){
		if(method_exists($this,$action)) return $this -> $action($text,$row);
		return "no method:".$action;
	}	
	public function addartical($text,$row){
		return "<a href='/privilege/addarticalex?q=".$row['link']."'>".$text."</a>";
	}	
	public function readartical($text,$row){
		return "<a href='/".$row['channel']."/".$row["urlkey"]."' target='_blank'>".$row["urlkey"]."</a>";
	}
	public function edit($text,$row) {
		return "<a href='/privilege/editartical?pk=".$row['id']."'>".$text."</a>";	
	}
	public function remove($text,$row) {
		return "<a onclick='return confirm(\"真的要删除?\")' href='/privilege/removeartical?pk=".$row['id']."'>".$text."</a>";	
	}
	public function view($text,$row) {
		return "<a href='/privilege/viewartical?pk=".$row['id']."'>".$text."</a>";	
	}

}

class commentconfig implements IuserFunction{
	public function execute($row,$action,$text){
		if(method_exists($this,$action)) return $this -> $action($text,$row);
		return "no method:".$action;
	}	
	public function edit($text,$row) {
		return "<a href='/privilege/editcomment?pk=".$row['id']."'>".$text."</a>";	
	}
	public function remove($text,$row) {
		return "<a onclick='return confirm(\"真的要删除?\")' href='/privilege/removecomment?pk=".$row['id']."'>".$text."</a>";	
	}
}

class systemuserconfig implements IuserFunction{
	public function execute($row,$action,$text){
		if(method_exists($this,$action)) return $this -> $action($text,$row);
		return "no method:".$action;
	}	
	public function remove($text,$row) {
		return "<a onclick='return confirm(\"真的要删除?\")' href='/privilege/removesystemuser?pk=".$row['id']."'>".$text."</a>";	
	}
	public function edit($text,$row) {
		return "<a href='/privilege/editsystemuser?pk=".$row['id']."'>".$text."</a>";	
	}
	public function updatepassword($v) {
		if(strlen($v) === 32){
			return $v;	
		}
		return md5($v);
	}
}


class feedbackconfig implements IuserFunction{
	public function execute($row,$action,$text){
		if(method_exists($this,$action)) return $this -> $action($text,$row);
		return "no method:".$action;
	}	
	public function showmsg($text,$row) {
		$d = json_decode($row['msg'],true);
		return ($d['msg']);
	}
	public function showallmsg($text,$row) {
		$d = json_decode($row['msg'],true);
		return P($d);
	}
	public function edit($text,$row) {
		return "<a href='/privilege/editfeedback?pk=".$row['id']."'>".$text."</a>";	
	}
	public function verify($text,$row) {
		if($row['verification'] === 'show'){
			return "<a href='/privilege/unverifyfeedback?pk=".$row['id']."'><span style='color:red'>禁止</span></a>";;	
		}
		return "<a href='/privilege/verifyfeedback?pk=".$row['id']."'>".$text."</a>";	
	}
	public function remove($text,$row) {
		return "<a onclick='return confirm(\"真的要删除?\")' href='/privilege/removefeedback?pk=".$row['id']."'>".$text."</a>";	
	}
}