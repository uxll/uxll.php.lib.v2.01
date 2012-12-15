<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: ui.php
 */
class mobileDisplayUI{

	private $isValid = false;
	
	private $headerjs ='';
	private $channel ='';
	private $title = '';
	private $bodyhtml ='';
	private $footerjs = '';
	private $diyjs = '';
	private $type = '';
	private $channelKey = '';
	private $current_channel = '';
	private $templateVars = '';
	public function isValid(){
		return $this -> isValid;	
	}
	public function __construct($type,$current_channel,$headerjs,$title,$keywords,$description,$bodyhtml,$footerjs){
		$r = $this -> check($type,$current_channel,$headerjs,$title,$keywords,$description,$bodyhtml,$footerjs);
		if($r){
			$this -> type = $type;
			$this -> headerjs = $headerjs;
			$this -> current_channel = $current_channel;
			$this -> title = $title;
			$this -> bodyhtml = $bodyhtml;
			$this -> footerjs = $footerjs;
			$this -> keywords = $keywords;
			$this -> description = $description;
		}
	}
	public function channel($vm,$pos,$count,$artical_count=5){
		$mobileData = new mobileData();
		return $mobileData -> channel($vm,$pos,$count,$artical_count);		
	}
	public function oparr($mode,$arr,$item){
		$mobileData = new mobileData();
		return $mobileData -> oparr($mode,$arr,$item);
	}
	public function getDeviceWidth() {
		return advancedMobile();
	}
	public function isPc() {
		return isPc();
	}
	public function display($vm,$iconchannel,$row=5){
		return mobileData::display($vm,$iconchannel,$row);
	}
	public function getHeaderjs(){
		return $this -> headerjs;	
	}
	public function getTitle(){
		return $this -> title;	
	}
	public function getBodyhtml(){
		return $this -> bodyhtml;	
	}
	public function setBodyhtml($html){
		$this -> bodyhtml = $html;	
	}
	public function getFooterjs(){
		return $this -> footerjs;	
	}
	public function getKeywords(){
		return $this -> keywords;	
	}
	public function getDescription(){
		return $this -> description;	
	}
	public function setExtraJS($js){
		$this -> diyjs = $js;	
	}
	public function getExtraJS(){
		return $this -> diyjs;	
	}
	public function getChannelKey() {
		return $this -> current_channel;
	}
	public function getChannelValue() {
		return $this -> channel[$this -> current_channel];
	}			
	public function getNav() {
		if($this -> getType() === 'home'){
			return '';
		}else if($this -> getType() === 'feedback'){
			return "<a href='/'>主页</a> > 患者点评";
		}else if($this -> getType() === 'board'){
			return "<a href='/'>主页</a> > 请您留言";
		}else{
			if($this -> getType() === "channel"){
				$cc = $this -> channel;
				#die(P($this -> getChannelKey()));
				return "<a href='/'>主页</a> > ".$cc[$this -> getChannelKey()];
			}else{
				$cc = $this -> channel;
				return "<a href='/'>主页</a> > <a href='/".$this -> getChannelKey()."'>".$cc[$this -> getChannelKey()]."</a> > ".$this -> title;
			}
		}
	}
	public function setChannel($txt) {
		$this -> channel = $txt;
	}
	public function setTemplateVars($tv){
		$this -> templateVars = $tv;	
	}
	public function gettemplatevars() {
		return $this -> templateVars;
	}
	public function getType(){
		return $this -> type;	
	}
	public function debug($var) {
		echo P($var);
		die();
	}
	public function getMenu($arr,$colspan=3){

		$html = '
			<div class="topmenu">
			<table cellspacing="0" cellpadding="0">
		';
		$len = count($arr);
		$total = ceil($len / $colspan) * $colspan;
		for($i=0;$i<$len;$i++){
			if(($i % $colspan) === 0){
				$html .= '<tr>';
			}
			if(isset($arr[$i])){
				$html .= '<td><a href="'.$arr[$i][0].'">'.$arr[$i][1].'</a></td>';
			}else{
				$html .= '<td></td>';	
			}
			if(($i +1) % $colspan === 0 ){
				$html .= '</tr>';
			}
			
		}
		$html .= '</table>
			</div>	
		';	

		return $html;
	}
	
//#2012/12/4 13:06:48
	public function artical($channel,$listnumofpage=30,$fields=""){
		$mobileData = new mobileData();
		return $mobileData -> artical($channel,$listnumofpage,$fields);		
	}
//public function displayAsDoctor()
//------------------------------------------------------------------------------------------------	
	private function check($type,$current_channel,$headerjs,$title,$keywords,$description,$bodyhtml,$footerjs){

		if(!is_string($type))return false;
		if(!is_string($current_channel))return false;
		if(!is_string($headerjs))return false;
		if(!is_string($title))return false;
		if(!is_string($bodyhtml))return false;
		if(!is_string($footerjs))return false;
		if(!is_string($keywords))return false;
		if(!is_string($description))return false;
		$this -> isValid = true;
		return true;
	}
	
	
}