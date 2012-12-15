<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: model.php
 */
class privilegeModel extends CModel{
	private $error = 0;
	private $engine;
	public function getLastError(){
		return $this -> error;
	}	
	public function __construct(){
		parent::__construct();
	}	
	
############################################################
####resynchronic
############################################################	
	public function updateresynchronicsetting($url,$key){
		$r = $this -> table("preference")
		-> update(array(
			"c" => $url
		),"k='resynchronicurl'")
		-> update(array(
			"c" => $key
		),"k='connectkey'")
		-> isValid();
		if(!$r){
			$this -> error = "保存出错!";
			return false;
		}
		return true;	
		
	}
############################################################
####channel
############################################################

	public function getchannelbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("channel");
		return $tbhelper -> display($config,$privmask,$extConfig);
	}
	public function getchannelsearchbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("channel");
		return $tbhelper -> search($config,$privmask,$extConfig);
	}
	public function removechannel($where,$privmask) {
		$tbhelper = new tbhelper("channel");
		$r = $tbhelper -> remove($where,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function editchannel($where,$config,$privmask){
		$tbhelper = new tbhelper("channel");
		$r = $tbhelper -> edit($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function viewchannel($where,$config,$privmask){
		$tbhelper = new tbhelper("channel");
		$r = $tbhelper -> view($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function appendchannel($msg,$config,$privmask) {
		$tbhelper = new tbhelper("channel");
		$r = $tbhelper -> append($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function updatechannel($msg,$config,$privmask) {
		$tbhelper = new tbhelper("channel");
		$r = $tbhelper -> update($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function addchannel($config,$privmask) {
		$tbhelper = new tbhelper("channel");
		$r = $tbhelper -> add($config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
############################################################
####artical
############################################################

	public function getarticalbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("artical");
		return $tbhelper -> display($config,$privmask,$extConfig);
	}
	public function getarticalsearchbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("artical");
		return $tbhelper -> search($config,$privmask,$extConfig);
	}
	public function removeartical($where,$privmask) {
		$tbhelper = new tbhelper("artical");
		$r = $tbhelper -> remove($where,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function editartical($where,$config,$privmask){
		$tbhelper = new tbhelper("artical");
		$r = $tbhelper -> edit($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function viewartical($where,$config,$privmask){
		$tbhelper = new tbhelper("artical");
		$r = $tbhelper -> view($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function appendartical($msg,$config,$privmask) {

		$tbhelper = new tbhelper("artical");
		$r = $tbhelper -> append($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function updateartical($msg,$config,$privmask) {
		$tbhelper = new tbhelper("artical");
		$r = $tbhelper -> update($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function addartical($config,$privmask) {
		$tbhelper = new tbhelper("artical");
		$r = $tbhelper -> add($config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}	
############################################################
####keywords
############################################################

	public function getkeywordsbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("keywords");
		return $tbhelper -> display($config,$privmask,$extConfig);
	}
	public function getkeywordssearchbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("keywords");
		return $tbhelper -> search($config,$privmask,$extConfig);
	}
	public function removekeywords($where,$privmask) {
		$tbhelper = new tbhelper("keywords");
		$r = $tbhelper -> remove($where,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function editkeywords($where,$config,$privmask){
		$tbhelper = new tbhelper("keywords");
		$r = $tbhelper -> edit($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function viewkeywords($where,$config,$privmask){
		$tbhelper = new tbhelper("keywords");
		$r = $tbhelper -> view($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function appendkeywords($msg,$config,$privmask) {
		$tbhelper = new tbhelper("keywords");
		$r = $tbhelper -> append($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function updatekeywords($msg,$config,$privmask) {
		$tbhelper = new tbhelper("keywords");
		$r = $tbhelper -> update($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function addkeywords($config,$privmask) {
		$tbhelper = new tbhelper("keywords");
		$r = $tbhelper -> add($config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}		
############################################################
####commnet
############################################################

	public function getcommentbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("comment");
		return $tbhelper -> display($config,$privmask,$extConfig);
	}
	public function getcommentsearchbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("comment");
		return $tbhelper -> search($config,$privmask,$extConfig);
	}
	public function removecomment($where,$privmask) {
		$tbhelper = new tbhelper("comment");
		$r = $tbhelper -> remove($where,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function editcomment($where,$config,$privmask){
		$tbhelper = new tbhelper("comment");
		$r = $tbhelper -> edit($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function viewcomment($where,$config,$privmask){
		$tbhelper = new tbhelper("comment");
		$r = $tbhelper -> view($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function appendcomment($msg,$config,$privmask) {
		$tbhelper = new tbhelper("comment");
		$r = $tbhelper -> append($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function updatecomment($msg,$config,$privmask) {
		$tbhelper = new tbhelper("comment");
		$r = $tbhelper -> update($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function addcomment($config,$privmask) {
		$tbhelper = new tbhelper("comment");
		$r = $tbhelper -> add($config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
############################################################
####feedback
############################################################

	public function getfeedbackbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("feedback");
		return $tbhelper -> display($config,$privmask,$extConfig);
	}
	public function getfeedbacksearchbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("feedback");
		return $tbhelper -> search($config,$privmask,$extConfig);
	}
	public function removefeedback($where,$privmask) {
		$tbhelper = new tbhelper("feedback");
		$r = $tbhelper -> remove($where,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function editfeedback($where,$config,$privmask){
		$tbhelper = new tbhelper("feedback");
		$r = $tbhelper -> edit($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function viewfeedback($where,$config,$privmask){
		$tbhelper = new tbhelper("feedback");
		$r = $tbhelper -> view($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function appendfeedback($msg,$config,$privmask) {
		$tbhelper = new tbhelper("feedback");
		$r = $tbhelper -> append($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function updatefeedback($msg,$config,$privmask) {
		$tbhelper = new tbhelper("feedback");
		$r = $tbhelper -> update($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function addfeedback($config,$privmask) {
		$tbhelper = new tbhelper("feedback");
		$r = $tbhelper -> add($config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}	
	public function verifyfeedback($where) {
		
		$this -> table("feedback") -> update(array("verification" => "show"),$where);
		$r = parent::isValid();
		if(!$r){
			$this -> error = parent::getLastError();
			return false;
		}
		return true;
	}	
	public function unverifyfeedback($where) {
		
		$this -> table("feedback") -> update(array("verification" => "hide"),$where);
		$r = parent::isValid();
		if(!$r){
			$this -> error = parent::getLastError();
			return false;
		}
		return true;
	}	
############################################################	
	public function upload(){
		$upload = new CUpload();
		$upload -> upload('txt');
		if($upload -> isValid()){
			$this -> error = $upload -> getUploadFileName();
			return true;	
		}else{
			$this -> error = $upload -> getLastError();
			return false;	
		}
	}
	public function replaceuploadimage($newfilename){
		$upload = new CUpload();
		$upload -> upload('txt',$newfilename);
		if($upload -> isValid()){
			$this -> error = $upload -> getUploadFileName();
			return true;	
		}else{
			$this -> error = $upload -> getLastError();
			return false;	
		}
	}
	public function listTemplateFields(){
		return $this -> getTableDecription('channel');
	}	
	public function reviseTemplateLocationDo($msg){
		$this -> q("ALTER TABLE `channel` CHANGE `location` `location` ".$msg['txt']." CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '模板位置'");
		if(parent :: isValid()){
			return true;	
		}else{
			$this -> error = parent :: getLastError();
			return false;	
		}
	}	
############################################################		
####systemuser
############################################################

	public function getsystemuserbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("systemuser");
		return $tbhelper -> display($config,$privmask,$extConfig);
	}
	public function getsystemusersearchbodyhtml($config,$privmask,$extConfig) {
		$tbhelper = new tbhelper("systemuser");
		return $tbhelper -> search($config,$privmask,$extConfig);
	}
	public function removesystemuser($where,$privmask) {
		$tbhelper = new tbhelper("systemuser");
		$r = $tbhelper -> remove($where,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function editsystemuser($where,$config,$privmask){
		$tbhelper = new tbhelper("systemuser");
		$r = $tbhelper -> edit($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function viewsystemuser($where,$config,$privmask){
		$tbhelper = new tbhelper("systemuser");
		$r = $tbhelper -> view($where,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}
	public function appendsystemuser($msg,$config,$privmask) {
		$tbhelper = new tbhelper("systemuser");
		$r = $tbhelper -> append($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function updatesystemuser($msg,$config,$privmask) {
/**/	
		$role = R("USER");
		if('system' !== $role['priv']){
			if(!isset($msg['id'])){
				$this -> error = 'wrong submit data';
				return false;	
			}
			if(!is_array($msg['id'])){
				$this -> error = 'wrong submit data';
				return false;	
			}
			if(1 !== count($msg['id'])){
				$this -> error = 'wrong submit data';
				return false;	
			}
			$_t = array_values($msg['id']);
			$_t = $_t[0];
			$role = R("USER");
			$id = $this -> table("systemuser") -> fields("id")
			-> where('`name` = \''.$role['name'].'\'') -> select()
			-> all();
			if(!is_array($id)){
				$this -> error = 'no id you special';
				return false;		
			}
			if(1 !== count($id)){
				$this -> error = 'no id you special';
				return false;		
			}
			if($id[0]['id'] !== $_t){
				$this -> error = 'please revise your profile...';
				return false;	
			}
		}
		$tbhelper = new tbhelper("systemuser");
		$r = $tbhelper -> update($msg,$config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return true;
	}
	public function addsystemuser($config,$privmask) {
		$tbhelper = new tbhelper("systemuser");
		$r = $tbhelper -> add($config,$privmask);
		if(!$r){
			$this -> error = $tbhelper -> getLastError();
			return false;
		}
		return $r;
	}	
############################################################
	public function setsuperpassword($ps){
		return CCache::update(CONFIGROOT,"apikey.php","<?php\r\nreturn \"".$ps."\";");	
	}	
	public function getSettingData() {
		$ret = array();
		$ret['themes'] = $this -> _getAllThemes();
		$ret['currentTel'] = $this -> getTemplateVar('tel');
		$ret['listnumofpage'] = $this -> getTemplateVar('listnumofpage');
		$ret['toolbar'] = $this -> getToolbarConfig();
		$ret['registeredVar'] = $this -> getAllTemplateVar();
		$ret['diyvars'] = $this -> getAllDiyTemplateVar();
		$ret['allowcomment'] = $this -> getTemplateVar('allowcomment');
		$ret['allowrelativeartical'] = $this -> getTemplateVar('allowrelativeartical');
		$ret['resynchronicurl'] = $this -> getTemplateVar('resynchronicurl');
		$ret['connectkey'] = $this -> getTemplateVar('connectkey');
		$ret['allowautopop'] = $this -> getTemplateVar('allowautopop');
		$ret['remindemail'] = $this -> getTemplateVar('remindemail');
		$ret['remindsms'] = $this -> getTemplateVar('remindsms');
		$ret['allowautosendemailtoremind'] = $this -> getTemplateVar('allowautosendemailtoremind');
		$ret['allowautosendsmstoremind'] = $this -> getTemplateVar('allowautosendsmstoremind');
		$ret["remindsmscontent"] = file_get_contents(ROOT."assets/config/smsremind.txt");
		return $ret;
	}
	public function updateallowcomment($c) {
		return $this -> updateTemplateVar('allowcomment',$c)
		
		;
	}
	public function setRemindEmail($c,$v) {
		return $this -> updateTemplateVar('remindemail',$c)
		&& $this -> updateTemplateVar('allowautosendemailtoremind',$v);
		;
	}
	public function setRemindSms($c,$v,$x) {
		return $this -> updateTemplateVar('remindsms',$c)
		&& $this -> updateTemplateVar('allowautosendsmstoremind',$v)
		&& file_put_contents(ROOT."assets/config/smsremind.txt",$x)
		;
	}
	public function updateallowrelativeartical($c) {
		return $this -> updateTemplateVar('allowrelativeartical',$c);
	}
	public function updateallowautopop($c) {
		return $this -> updateTemplateVar('allowautopop',$c);
	}
	public function getSettingCurrentTheme() {
		$rs = $this -> table("preference")
		-> fields("c") -> where("`k` = 'theme' and `f` = 'sys'")
		-> select() -> all();
		if(is_array($rs) && count($rs) === 1){
			return $rs[0]['c'];
		}
		return 'south-street';
	}
	public function settingCurrentTheme($currentTheme) {
		return $this -> updateTemplateVar('theme',$currentTheme);
	}
	public function updateTemplate($t_var,$content) {
		return $this -> updateTemplateVar($t_var,$content);
	}
	public function getTemplateVar($t_var,$sys = 'sys') {
		$rs = $this -> table("preference")
		-> fields("c") -> where("`k` = '".$t_var."' and `f` = '".$sys."'")
		-> select() -> all();
		if(is_array($rs) && count($rs) === 1){
			return $rs[0]['c'];
		}
		return false;
	}
	public function updatediyvar($i,$k,$v,$c) {
		$r = $this -> table("preference") -> update(array(
			"k" => $k
			,"v" => $v
			,"c" => $c
		),$i);
		if(parent::isValid()){
			return true;	
		}
		$this -> error = parent::getLastError();
		return false;
	}
	private function _getAllThemes() {
		$dir = THIRDLIBROOT."ui/jquery/themes/";
		$ret = array();
		$handle = opendir($dir);
		readdir($handle);
		readdir($handle);
		while (false !== ($file = readdir($handle))) {
			if (is_dir($dir.'/'.$file)){
				$ret[] = $file;
			} 
		}
		closedir($handle);
		return $ret;
	}	
	private function updateTemplateVar($var,$content,$sys='sys') {
		$r = $this -> table("preference") -> update(array("c" => $content),"`k` = '".$var."' and `f`='".$sys."'");
		if(parent::isValid()){
			return true;	
		}
		$this -> error = parent::getLastError();
		return false;
	}
	private function getToolbarConfig() {
		return array(
			"js" => CCache::read(THIRDLIBROOT."ueditor/editor_config.js")
			,"css" => CCache::read(THIRDLIBROOT."ueditor/themes/default/ueditor.css")
		);
	}
	private function getAllTemplateVar() {
		$r = $this -> table("preference") -> fields("k") -> select() -> all();
		if(!is_array($r))return array();
		$ret = array();
		foreach($r as $t){
			$ret[] = $t['k'];	
		}
		return $ret;
	}
	private function getAllDiyTemplateVar() {
		$r = $this -> table("preference") -> fields("`id`,`f`,`k`,`v`,`c`") -> where("`f`='diy'") -> order("`id` asc") -> select() -> all();
		if(!is_array($r))return array();
		return $r;
	}
//**************************************************************
	public function getchannelforartical(){
		$ori = $this -> table("channel") -> fields("text,link")
		-> select() -> all()
		;
		$ret = array();
		foreach($ori as $val){
			$ret[$val["link"]] = $val["text"];
		}
		return $ret;
	}	
	
	
	public function resynchronic_artical_channel(){
		$ori = $this -> table("channel") -> fields("link")
		-> select() -> all()
		;
		$ret = array();
		foreach($ori as $val){
			$ret[] = $val["link"];
		}
		$this -> q("ALTER TABLE `artical` CHANGE `channel` `channel` ENUM('".join("','",$ret)."') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL COMMENT '所属频道'");
		return parent::isValid();	
	}
}