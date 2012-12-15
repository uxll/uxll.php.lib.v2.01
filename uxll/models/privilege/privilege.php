<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: privilege.php
 */
define('DIY_UXLL_TBHELPER_CONFIG',1);
define('UXLL_UPLOAD_IMAGES_PATH',"assets/uploads/images/");
class privilege extends CauthController{
	private $error='';
	private $login;
	private $_last_update_return_url = 'last_update_return_url';
	private $_need_resynchronicic = array('Channel','List');
	static public $_resynchronicartical_msg = '';
	public function getLastError(){
		return $this -> error;
	}
	public function __construct(){
		require_once(MODELROOT.'privilege/view.php');
		require_once(MODELROOT.'privilege/model.php');
		require_once(EXTROOT.'login/userAuth.php');
		require_once(EXTROOT.'tbhelper/tbhelper.php');
		require_once(MODELROOT.'privilege/common/ui.php');

		require_once(EXTROOT.'tbhelper/common/userFunction.php');
		require_once(MODELROOT.'privilege/common/userFunctions.php');

		
		$this -> model = new privilegeModel();
		$currentTheme = $this -> model -> getSettingCurrentTheme();
		$this -> view = new privilegeView($currentTheme);
		

		$this -> login = new userAuth();
	}	

	public function checkPrivilege(CMessage $msg,CIdentityToken $it){
		R('USER',$it);
#var_export(R('USER'));		
		$login = new userAuth();
		$r = $login -> check($msg);
		if($r){
			
			return true;
		}else{
			$this -> view -> go('/login');
			return false;
		}
	}	
	
	public function welcome($msg){
		$this -> view -> welcome();
	}
	public function upgrade($msg){
		$this -> view -> upgrade();
	}
	public function refreashcache($msg){
		if(is_dir(ROOT.'dc/cache/'))CCache::removeDir(ROOT.'dc/cache/');
		$this -> view -> success('','缓存已清除',2);	
	}
	public function resynchronic($msg){
		$data = $this -> model -> getSettingData();
		$this -> view -> resynchronicUI($data);
	}
	public function resynchronicDo($msg){
		S('_last_resynchronicDo_post_data',$msg);
		$this -> view -> resynchronicDoUI();
	}
	public function resynchronicing(){
		$msg = S('_last_resynchronicDo_post_data');
		S('_last_resynchronicDo_post_data',null,'unset');
		
		$setting_data = $this -> model -> getSettingData();
		$connecting_url = $setting_data['resynchronicurl'];
		$connecting_key = $setting_data['connectkey'];
		if(isset($msg['resynchronictype_db_channel']) && $msg['resynchronictype_db_channel'] === 'on'){
			$tbname = 'channel';
			$this -> resynchronicingDB($setting_data,$tbname);
			echo "准备同步文章表结构<br>";
			$this -> _resynchronic_artical_channel();
			echo "同步文章表结构同步完成...<br>";
		}		
		if(isset($msg['resynchronictype_db_artical']) && $msg['resynchronictype_db_artical'] === 'on'){
			$tbname = 'artical';
			$this -> resynchronicingDB($setting_data,$tbname);
		}

		if(isset($msg['resynchronictype_db_comment']) && $msg['resynchronictype_db_comment'] === 'on'){
			$tbname = 'comment';
			$this -> resynchronicingDB($setting_data,$tbname);
		}
		if(isset($msg['resynchronictype_db_feedback']) && $msg['resynchronictype_db_feedback'] === 'on'){
			$tbname = 'feedback';
			$this -> resynchronicingDB($setting_data,$tbname);
		}
		if(isset($msg['resynchronictype_db_keywords']) && $msg['resynchronictype_db_keywords'] === 'on'){
			$tbname = 'keywords';
			$this -> resynchronicingDB($setting_data,$tbname);
		}
		if(isset($msg['resynchronictype_db_preference']) && $msg['resynchronictype_db_preference'] === 'on'){
			$tbname = 'preference';
			$this -> resynchronicingDB($setting_data,$tbname);
		}
		if(isset($msg['resynchronictype_db_systemuser']) && $msg['resynchronictype_db_systemuser'] === 'on'){
			$tbname = 'systemuser';
			$this -> resynchronicingDB($setting_data,$tbname);
		}
		if(isset($msg['resynchronictype_setting_updatetime'])){
			$cond = $msg['resynchronictype_setting_updatetime'];
		}else{
			$cond = 12;	
		}
		if(isset($msg['resynchronictype_file_template']) && $msg['resynchronictype_file_template'] === 'on'){
			$this -> resynchronicingFile($setting_data,'template',$cond);
		}
		if(isset($msg['resynchronictype_file_uploadimages']) && $msg['resynchronictype_file_uploadimages'] === 'on'){
			$this -> resynchronicingFile($setting_data,'uploadimages',$cond);
		}
		echo "<hr><font color=green>网站同步完成...</font>";
	}
	public function resynchronicingDB($setting_data,$tbname){
		$connecting_url = $setting_data['resynchronicurl'];
		$connecting_key = $setting_data['connectkey'];

		require_once(COMMONROOT.'httppost.php');
		httppost(
			$connecting_url.'/resynchronic?key='.$connecting_key.'&type=db&args='.$tbname,
			"privilege::_resynchronicartical",
			array()
		);
		R('CONTROL') -> route(new CMessage(array(
			"control" => "resynchronic",
			"action" => "set",
			"arguments" => array(
				"key" => $connecting_key
			)
		),
		array(
			"db" => R("__tpl__452eemdie")
		)));
		echo "表".$tbname."同步成功...<br>";	

	}
	public function resynchronicingFile($setting_data,$what,$cond){
		$connecting_url = $setting_data['resynchronicurl'];
		$connecting_key = $setting_data['connectkey'];

		require_once(COMMONROOT.'httppost.php');
		httppost(
			$connecting_url.'/resynchronic?key='.$connecting_key.'&type=file&args='.$what.'&extra='.$cond,
			"privilege::_resynchronicartical",
			array()
		);
		R('CONTROL') -> route(new CMessage(array(
			"control" => "resynchronic",
			"action" => "set",
			"arguments" => array(
				"key" => $connecting_key
			)
		),
		array(
			"file" => R("__tpl__452eemdie")
		)));
		echo $what."同步完成...<br>";	

	}
	public function resynchronicsetting($msg){
		if(isset($msg['url']) && isset($msg['apikey'])){
			if(!preg_match("/^https?:\/\/[a-zA-Z0-9\.\-\_]+$/",$msg['url'])){
				$this -> view -> fail("","要连接的网址格式不正确",3);
				return;	
			}
			if(strlen($msg['apikey']) === 0){
				$this -> view -> fail("","请填写通信密码",3);
				return;	
			}
			$r = $this -> model -> updateresynchronicsetting($msg['url'],$msg['apikey']);
			if($r){
				$this -> view -> success("","操作成功",3);	
				return true;
			}else{
				$this -> view -> fail("",$this -> getLastError(),5);
				return false;	
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;
		}
	}
	static public function _resynchronicartical($msg){
		R("__tpl__452eemdie",$msg);
	}
	private function _echo_resynchronicInfo($info){
		static $f = 0;
		if($f){
			echo str_pad(" ", 256 * 16);
			$f = 1;
		}
		echo $info;
		echo "<br>";
		ob_flush();  
		flush();
	}

#################################################################
####channel	
#################################################################	
	public function channel($msg){
		$config = $this -> _get_config('channel',"display");
		//die(P($config));
		$privMask = $this -> _get_priv_mask("channel");
		$bodyhtml = $this -> model -> getchannelbodyhtml($config ,$privMask,array(
			"displayNum" => 10
		));
		$this -> view -> channel($bodyhtml);
	}	
	public function addchannel($msg){
		$config = $this -> _get_config('channel',"add");
		$privmask = $this -> _get_priv_mask("channel");
		$bodyhtml = $this -> model -> addchannel($config,$privmask);
		$this -> view -> addchannel($bodyhtml);		
	}
	public function appendchannel($msg){

		$config = $this -> _get_config('channel',"append");
		$privmask = $this -> _get_priv_mask("channel");

		$r = $this -> model -> appendchannel($msg,$config,$privmask);
		if($r){
			if($this -> _resynchronic_artical_channel()){
				$this -> view -> success("","添加成功",3);	
			}else{
				$this -> view -> fail("",'同步文章中的频道失败',50);
			}
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function searchchannel($msg){
		$config = $this -> _get_config('channel',"display");
		$privMask = $this -> _get_priv_mask("channel");
		$bodyhtml = $this -> model -> getchannelsearchbodyhtml($config ,$privMask,array(
			"where" => $msg[':q'] 
			,"displayNum" => 15
		));
		$this -> view -> channel($bodyhtml);
	}
	public function editchannel($msg) {
		if(false === strpos(R("HTTP") -> frontUrl(), "updatechannel")){
			S($this -> _last_update_return_url,R("HTTP") -> frontUrl());
		}
		
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('channel'));
		$config = $this -> _get_config('channel',"edit");
#		echo P($config);
		$privmask = $this -> _get_priv_mask("channel");
		$bodyhtml = $this -> model -> editchannel($where,$config,$privmask);
		$this -> view -> editchannel($bodyhtml);	
	}
	public function viewchannel($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('channel'));
		$config = $this -> _get_config('channel',"view");
		$privmask = $this -> _get_priv_mask("channel");
		$bodyhtml = $this -> model -> viewchannel($where,$config,$privmask);
		$this -> view -> viewchannel($bodyhtml);	
	}
	public function updatechannel($msg){
		$config = $this -> _get_config('channel',"update");
		$privmask = $this -> _get_priv_mask("channel");
		$r = $this -> model -> updatechannel($msg,$config,$privmask);
		if($r){
			if($this -> _resynchronic_artical_channel()){
				$this -> view -> success(S($this -> _last_update_return_url),"更新成功",3);	
			}else{
				$this -> view -> fail("",'同步文章中的频道失败',50);
			}
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function removechannel($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('channel'));
		$privMask = $this -> _get_priv_mask("channel");
		$r = $this -> model -> removechannel($where,$privMask);
		if($r){
			if($this -> _resynchronic_artical_channel()){
				$this -> view -> success("","删除成功",3);	
			}else{
				$this -> view -> fail("",'同步文章中的频道失败',50);
			}
			
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
#################################################################
####artical	
#################################################################	
	public function artical($msg){
		$config = $this -> _get_config('artical',"display");
		$config["fieldsmanifest"]["channel"] = $this -> model -> getchannelforartical();
		$privMask = $this -> _get_priv_mask("artical");
		$bodyhtml = $this -> model -> getarticalbodyhtml($config ,$privMask,array(
			"displayNum" => 10
		));
		$this -> view -> artical($bodyhtml);
	}	
	public function addarticalex($msg) {
		if(isset($msg[':q'])){
			$config = $this -> _get_config('artical',"add");
			$_t = $this -> model -> getchannelforartical();
			$config["fieldsmanifest"]["channel"] = array(
				"extravalue" => array(
					"defaultvalue" => $msg[':q']
					,"enum" => array_keys($_t)	
					,"value" => array_values($_t)
				)
			);
			$privmask = $this -> _get_priv_mask("artical");
			$bodyhtml = $this -> model -> addartical($config,$privmask);
			$this -> view -> addartical($bodyhtml);	
		}else{
			return $this -> artical($msg);	
		}
	}
	public function addartical($msg){
		$config = $this -> _get_config('artical',"add");
		$_t = $this -> model -> getchannelforartical();
		$config["fieldsmanifest"]["channel"] = array(
			"value" => array_values($_t)
			,"enum" => array_keys($_t)	
		);
		$privmask = $this -> _get_priv_mask("artical");
		$bodyhtml = $this -> model -> addartical($config,$privmask);
		$this -> view -> addartical($bodyhtml);		
	}
	public function appendartical($msg){
		$config = $this -> _get_config('artical',"append");
		$privmask = $this -> _get_priv_mask("artical");
		$r = $this -> model -> appendartical($msg,$config,$privmask);
		if($r){
			if(isset($msg[':q'])){
				$url = "privilege/addarticalex?q=".$msg[':q'];	
			}else{
				$url = "";	
			}
			$this -> view -> success($url,"添加成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function searchartical($msg){
		$config = $this -> _get_config('artical',"display");
		$privMask = $this -> _get_priv_mask("artical");
		$bodyhtml = $this -> model -> getarticalsearchbodyhtml($config ,$privMask,array(
			"where" => $msg[':q'] 
			,"displayNum" => 15
		));
		$this -> view -> artical($bodyhtml);
	}
	public function editartical($msg) {
		S($this -> _last_update_return_url,R("HTTP") -> frontUrl());
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('artical'));
		$config = $this -> _get_config('artical',"edit");
#		echo P($config);
		$_t = $this -> model -> getchannelforartical();
		$config["fieldsmanifest"]["channel"] = array(
			"value" => array_values($_t)
			,"enum" => array_keys($_t)	
		);
		$privmask = $this -> _get_priv_mask("artical");
		$bodyhtml = $this -> model -> editartical($where,$config,$privmask);
		$this -> view -> editartical($bodyhtml);	
	}
	public function viewartical($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('artical'));
		$config = $this -> _get_config('artical',"view");
		$privmask = $this -> _get_priv_mask("artical");
		$bodyhtml = $this -> model -> viewartical($where,$config,$privmask);
		$this -> view -> viewartical($bodyhtml);	
	}
	public function updateartical($msg){
		$config = $this -> _get_config('artical',"update");
		$privmask = $this -> _get_priv_mask("artical");
		$r = $this -> model -> updateartical($msg,$config,$privmask);
		if($r){
			$this -> view -> success(S($this -> _last_update_return_url),"更新成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function removeartical($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('artical'));
		$privMask = $this -> _get_priv_mask("artical");
		$r = $this -> model -> removeartical($where,$privMask);
		if($r){
			$this -> view -> success("","删除成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}		
#################################################################
####keywords	
#################################################################	
	public function seosetting($msg){
		$this -> keywords($msg);
	}
	public function link($msg){
		$this -> keywords($msg);
	}			
	public function keywords($msg){
		$config = $this -> _get_config('keywords',"display");
		$privMask = $this -> _get_priv_mask("keywords");
		$bodyhtml = $this -> model -> getkeywordsbodyhtml($config ,$privMask,array(
			"displayNum" => 10
		));
		$this -> view -> keywords($bodyhtml);
	}	
	public function addkeywords($msg){
		$config = $this -> _get_config('keywords',"add");
		$privmask = $this -> _get_priv_mask("keywords");
		$bodyhtml = $this -> model -> addkeywords($config,$privmask);
		$this -> view -> addkeywords($bodyhtml);		
	}
	public function appendkeywords($msg){
		$config = $this -> _get_config('keywords',"append");
		$privmask = $this -> _get_priv_mask("keywords");
		$r = $this -> model -> appendkeywords($msg,$config,$privmask);
		if($r){
			$this -> view -> success("","添加成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function searchkeywords($msg){
		$config = $this -> _get_config('keywords',"display");
		$privMask = $this -> _get_priv_mask("keywords");
		$bodyhtml = $this -> model -> getkeywordssearchbodyhtml($config ,$privMask,array(
			"where" => $msg[':q'] 
			,"displayNum" => 15
		));
		$this -> view -> keywords($bodyhtml);
	}
	public function editkeywords($msg) {
		S($this -> _last_update_return_url,R("HTTP") -> frontUrl());
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('keywords'));
		$config = $this -> _get_config('keywords',"edit");
		$privmask = $this -> _get_priv_mask("keywords");
		$bodyhtml = $this -> model -> editkeywords($where,$config,$privmask);
		$this -> view -> editkeywords($bodyhtml);	
	}
	public function viewkeywords($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('keywords'));
		$config = $this -> _get_config('keywords',"view");
		$privmask = $this -> _get_priv_mask("keywords");
		$bodyhtml = $this -> model -> viewkeywords($where,$config,$privmask);
		$this -> view -> viewkeywords($bodyhtml);	
	}
	public function updatekeywords($msg){
		$config = $this -> _get_config('keywords',"update");
		$privmask = $this -> _get_priv_mask("keywords");
		$r = $this -> model -> updatekeywords($msg,$config,$privmask);
		if($r){
			$this -> view -> success(S($this -> _last_update_return_url),"更新成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function removekeywords($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('keywords'));
		$privMask = $this -> _get_priv_mask("keywords");
		$r = $this -> model -> removekeywords($where,$privMask);
		if($r){
			$this -> view -> success("","删除成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}	
#################################################################
####comment	
#################################################################	

	public function comment($msg){
		$config = $this -> _get_config('comment',"display");
		$privMask = $this -> _get_priv_mask("comment");
		$bodyhtml = $this -> model -> getcommentbodyhtml($config ,$privMask,array(
			"displayNum" => 10
		));
		$this -> view -> comment($bodyhtml);
	}	
	public function addcomment($msg){
		$config = $this -> _get_config('comment',"add");
		$privmask = $this -> _get_priv_mask("comment");
		$bodyhtml = $this -> model -> addcomment($config,$privmask);
		$this -> view -> addcomment($bodyhtml);		
	}
	public function appendcomment($msg){
		$config = $this -> _get_config('comment',"append");
		$privmask = $this -> _get_priv_mask("comment");
		$r = $this -> model -> appendcomment($msg,$config,$privmask);
		if($r){
			$this -> view -> success("","添加成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function searchcomment($msg){
		$config = $this -> _get_config('comment',"display");
		$privMask = $this -> _get_priv_mask("comment");
		$bodyhtml = $this -> model -> getcommentsearchbodyhtml($config ,$privMask,array(
			"where" => $msg[':q'] 
			,"displayNum" => 15
		));
		$this -> view -> comment($bodyhtml);
	}
	public function editcomment($msg) {
		if(false === strpos(R("HTTP") -> frontUrl(), "updatecomment")){
			S($this -> _last_update_return_url,R("HTTP") -> frontUrl());
		}
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('comment'));
		$config = $this -> _get_config('comment',"edit");
		$privmask = $this -> _get_priv_mask("comment");
		$bodyhtml = $this -> model -> editcomment($where,$config,$privmask);
		$this -> view -> editcomment($bodyhtml);	
	}
	public function viewcomment($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('comment'));
		$config = $this -> _get_config('comment',"view");
		$privmask = $this -> _get_priv_mask("comment");
		$bodyhtml = $this -> model -> viewcomment($where,$config,$privmask);
		$this -> view -> viewcomment($bodyhtml);	
	}
	public function updatecomment($msg){
		$config = $this -> _get_config('comment',"update");
		$privmask = $this -> _get_priv_mask("comment");
		$r = $this -> model -> updatecomment($msg,$config,$privmask);
		if($r){
			$this -> view -> success(S($this -> _last_update_return_url),"更新成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function removecomment($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('comment'));
		$privMask = $this -> _get_priv_mask("comment");
		$r = $this -> model -> removecomment($where,$privMask);
		if($r){
			$this -> view -> success("","删除成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}	
#################################################################
####feedback	
#################################################################	

	public function feedback($msg){
		$config = $this -> _get_config('feedback',"display");
		$privMask = $this -> _get_priv_mask("feedback");
		$bodyhtml = $this -> model -> getfeedbackbodyhtml($config ,$privMask,array(
			"displayNum" => 10
		));
		$this -> view -> feedback($bodyhtml);
	}	
	public function addfeedback($msg){
		$config = $this -> _get_config('feedback',"add");
		$privmask = $this -> _get_priv_mask("feedback");
		$bodyhtml = $this -> model -> addfeedback($config,$privmask);
		$this -> view -> addfeedback($bodyhtml);		
	}
	public function appendfeedback($msg){
		$config = $this -> _get_config('feedback',"append");
		$privmask = $this -> _get_priv_mask("feedback");
		$r = $this -> model -> appendfeedback($msg,$config,$privmask);
		if($r){
			$this -> view -> success("","添加成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function searchfeedback($msg){
		$config = $this -> _get_config('feedback',"display");
		$privMask = $this -> _get_priv_mask("feedback");
		$bodyhtml = $this -> model -> getfeedbacksearchbodyhtml($config ,$privMask,array(
			"where" => $msg[':q'] 
			,"displayNum" => 15
		));
		$this -> view -> feedback($bodyhtml);
	}
	public function editfeedback($msg) {
		if(false === strpos(R("HTTP") -> frontUrl(), "updatefeedback")){
			S($this -> _last_update_return_url,R("HTTP") -> frontUrl());
		}
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('feedback'));
		$config = $this -> _get_config('feedback',"edit");
		$privmask = $this -> _get_priv_mask("feedback");
		$bodyhtml = $this -> model -> editfeedback($where,$config,$privmask);
		$this -> view -> editfeedback($bodyhtml);	
	}
	public function viewfeedback($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('feedback'));
		$config = $this -> _get_config('feedback',"view");
		$privmask = $this -> _get_priv_mask("feedback");
		$bodyhtml = $this -> model -> viewfeedback($where,$config,$privmask);
		$this -> view -> viewfeedback($bodyhtml);	
	}
	public function updatefeedback($msg){
		$config = $this -> _get_config('feedback',"update");
		$privmask = $this -> _get_priv_mask("feedback");
		$r = $this -> model -> updatefeedback($msg,$config,$privmask);
		if($r){
			$this -> view -> success(S($this -> _last_update_return_url),"更新成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function removefeedback($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('feedback'));
		$privMask = $this -> _get_priv_mask("feedback");
		$r = $this -> model -> removefeedback($where,$privMask);
		if($r){
			$this -> view -> success("","删除成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function verifyfeedback($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('feedback'));
		$r = $this -> model -> verifyfeedback($where);
		if($r){
			$this -> view -> success("","验证成功...",1);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),10);	
		}
	}
	public function unverifyfeedback($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('feedback'));
		$r = $this -> model -> unverifyfeedback($where);
		if($r){
			$this -> view -> success("","禁止成功...",1);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),10);	
		}
	}
#################################################################
###upload
#################################################################
	public function upload($msg){
		$this -> view -> upload();			
	}	
	public function uploadimg($msg){
		$r = $this -> model -> upload($msg);
		if($r){
			$this -> view -> success('','上传成功,你的文件路径是<input value="'.$this -> model -> getLastError().'">',30);
		}else{
			$this -> view -> fail('',$this -> model -> getLastError(),10);
		}
	}
	public function viewuploadedimage() {
		$this -> view -> viewupload();
	}
	public function removeuploadimg($msg) {
		if(!isset($msg[':p'])){
			echo "false";
		}
		$filename = explode("/",$msg[':p']);
		$filename = $filename[count($filename) -1];
		if(!$filename)return false;
		$r = CCache::romove(ROOT.UXLL_UPLOAD_IMAGES_PATH,$filename);
		echo $r ? "true" : "false";
	}
	public function replaceuploadimageui($msg) {
		if(!isset($msg[':i'])){
			$this -> view -> fail("","参数不正确",10);
			return false;
		}
		$this -> view -> upload("/privilege/replaceuploadimage?i=".$msg[':i']);	
		return false;
		
	}
	public function replaceuploadimage($msg) {
		if(!isset($msg[':i'])){
			$this -> view -> fail("","参数不正确",10);
			return false;
		}
		$r = $this -> model -> replaceuploadimage($msg[':i']);
		if($r){
			$this -> view -> success('','替换成功',2);
		}else{
			$this -> view -> fail('',$this -> model -> getLastError(),10);
		}	
		return false;
		
	}
#################################################################
###template
#################################################################
	public function template($msg){
		if(!isset($msg[':p'])){
			$msg[':p'] = 'index';	
		}
		if($msg[':p'] === 'css'){
			if(!isset($msg[':n'])){
				return $this -> view -> css($msg);
			}else{
				if(!file_exists(THEMEROOT."ui/css/".$msg[':n'].".css")){
					$this -> view -> fail("","你要找的CSS不存在",5);
					return;	
				}
			}
		}
		$this -> view -> template($msg);			
	}	
	public function savetemplate($msg) {
		if(!isset($msg[':p']) || !isset($msg['c'])){
			die("提交的参数不对");	
		}
		if($this -> _savetemplate_path($msg)){
			die("更新成功");	
		}else{
			die("更新失败");
		}
	}
	private function _savetemplate_path($msg){
		if($msg[':p'] !== "css"){
			return CCache::update(
				THEMEROOT."template/"
				,$msg[":p"].".htm"
				,$msg['c']
			)
			;
		}
		return CCache::update(
			THEMEROOT.'ui/css/'
			,$msg[':n'].".css"
			,$msg['c']
		)
		;
	}
#################################################################
###passworld ??? 2012/10/13 11:20:48
#################################################################	

	public function passworld($msg){
		$config = $this -> _get_config('systemuser',"display");
		$privMask = $this -> _get_priv_mask("systemuser");
		$bodyhtml = $this -> model -> getsystemuserbodyhtml($config ,$privMask,array(
			"displayNum" => 10
		));
		$this -> view -> systemuser($bodyhtml);			
	}
	public function addsystemuser($msg){
		$config = $this -> _get_config('systemuser',"add");
		$privmask = $this -> _get_priv_mask("systemuser");
		$bodyhtml = $this -> model -> addsystemuser($config,$privmask);
		$this -> view -> addsystemuser($bodyhtml);		
	}
	public function appendsystemuser($msg){
		$config = $this -> _get_config('systemuser',"append");
		$privmask = $this -> _get_priv_mask("systemuser");
		$r = $this -> model -> appendsystemuser($msg,$config,$privmask);
		if($r){
			$this -> view -> success("","添加成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function searchsystemuser($msg){
		$config = $this -> _get_config('systemuser',"display");
		$privMask = $this -> _get_priv_mask("systemuser");
		$bodyhtml = $this -> model -> getsystemusersearchbodyhtml($config ,$privMask,array(
			"where" => $msg[':q'] 
			,"displayNum" => 15
		));
		$this -> view -> systemuser($bodyhtml);
	}
	public function editsystemuser($msg) {
		if(false === strpos(R("HTTP") -> frontUrl(), "updatesystemuser")){
			S($this -> _last_update_return_url,R("HTTP") -> frontUrl());
		}
		$role = R("USER");
		if('system' !== $role['priv']){
			$where = "`name`= '".$role['name']."'";
		}else{
			$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('systemuser'));
		}
		
		$config = $this -> _get_config('systemuser',"edit");
		$privmask = $this -> _get_priv_mask("systemuser");
		$bodyhtml = $this -> model -> editsystemuser($where,$config,$privmask);
		$this -> view -> editsystemuser($bodyhtml);	
	}

	public function viewsystemuser($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('systemuser'));
		$config = $this -> _get_config('systemuser',"view");
		$privmask = $this -> _get_priv_mask("systemuser");
		$bodyhtml = $this -> model -> viewsystemuser($where,$config,$privmask);
		$this -> view -> viewsystemuser($bodyhtml);	
	}
	public function updatesystemuser($msg){
		$config = $this -> _get_config('systemuser',"update");
		$privmask = $this -> _get_priv_mask("systemuser");
		$r = $this -> model -> updatesystemuser($msg,$config,$privmask);
		if($r){
			$this -> view -> success(S($this -> _last_update_return_url),"更新成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
	public function removesystemuser($msg) {
		$where = $this -> _pks2towhere($msg,$this -> model -> getPrimaryKey('systemuser'));
		$privMask = $this -> _get_priv_mask("systemuser");
		$r = $this -> model -> removesystemuser($where,$privMask);
		if($r){
			$this -> view -> success("","删除成功",3);	
		}else{
			$this -> view -> fail("",$this -> model -> getLastError(),50);
		}
	}
#################################################################
###setting
#################################################################	
	public function setting($msg) {
		if(!isset($msg[':action'])){
			$this -> setting_default();
			return true;
		}
		switch ($msg[':action']){ 
			case "settheme":
				return $this -> setting_settheme($msg);
			case "updatetel":
				return $this -> setting_updatetel($msg);
			case "updatelistnumofpage":
				return $this -> setting_updatelistnumofpage($msg);	
			case "ueditortoolbar":
				return $this -> setting_ueditortoolbar($msg);	
			case "restoreueditortoolbar":
				return $this -> setting_restoreueditortoolbar($msg);
			case "updatediyvar":
				return $this -> setting_updatediyvar($msg);
			case "updateallowcomment":
				return $this -> setting_updateallowcomment($msg);	
			case "updateallowrelativeartical":
				return $this -> setting_updateallowrelativeartical($msg);						
			case "updateallowautopop":
				return $this -> setting_updateallowautopop($msg);
			case "setSuperPwd":
				return $this -> setting_superpassword($msg);
			case "setRemindEmail":
				return $this -> setting_setRemindEmail($msg);	
			case "setRemindSms":
				return $this -> setting_setRemindSms($msg);	
			default:
				$this -> view -> fail("","不知道你想干嘛",10);
		}
		
	}
	private function setting_default() {
		$data = $this -> model -> getSettingData();
		$data['superpassword'] = require(CONFIGROOT.'apikey.php');
		$this -> view -> setting($data);
	}
	private function setting_settheme($msg){
		$data = $this -> model -> getSettingData();
		if(isset($msg['c']) && in_array($msg['c'],$data['themes'])){
			$r = $this -> model -> settingCurrentTheme($msg['c']);	
			if($r){
				$this -> view -> success("","主题改变成功",3);
				return;
			}else{
				$this -> view -> fail("","主题改变失败",3);
				return;
			}
		}else{
			$this -> view -> fail("","主题改变失败",3);
			return;
		}
	}
	private function setting_updatetel($msg) {
		if(isset($msg['c'])){
			$r = $this -> model -> updateTemplate('tel',$msg['c']);	
			if($r){
				$this -> view -> success("","电话改变成功",3);
				return;
			}else{
				$this -> view -> fail("","电话改变失败",3);
				return;
			}
		}else{
			$this -> view -> fail("","电话改变失败",3);
			return;
		}
	}
	private function setting_updatelistnumofpage($msg) {
		if(isset($msg['c'])){
			$r = $this -> model -> updateTemplate('listnumofpage',$msg['c']);	
			if($r){
				$this -> view -> success("","列表页个数改变成功",3);
				return;
			}else{
				$this -> view -> fail("","列表页个数改变失败",3);
				return;
			}
		}else{
			$this -> view -> fail("","列表页个数改变失败",3);
			return;
		}
	}
	private function setting_ueditortoolbar($msg) {
		if(!isset($msg['js']) || !isset($msg['css'])){
			return $this -> view -> fail("","提交的参数不对",5);	
		}
		if(
			CCache::update(THIRDLIBROOT."ueditor/","editor_config.js",$msg['js'])
			&&
			CCache::update(THIRDLIBROOT."ueditor/themes/default/","ueditor.css",$msg['css'])
		){
			return $this -> view -> success("","更新成功",2);	
		}else{
			return $this -> view -> fail("","更新失败",5);
		}
	}
	private function setting_restoreueditortoolbar($msg) {
		if(
			CCache::update(THIRDLIBROOT."ueditor/","editor_config.js",CCache::read(ROOT."dc/bakup/editor_config.js"))
			&&
			CCache::update(THIRDLIBROOT."ueditor/themes/default/","ueditor.css",CCache::read(ROOT."dc/bakup/ueditor.css"))
		){
			return $this -> view -> success("","成功的用备份文件恢复",2);	
		}else{
			return $this -> view -> fail("","恢复失败，联系我",5);
		}
	}
	private function setting_updatediyvar($msg) {
		if(isset($msg['c']) && isset($msg['v']) && isset($msg['k']) && isset($msg[':i']) ){
			$v = new CIntegerValidator();
			if(!$v -> isValid($msg[':i'])){
				$this -> view -> fail("","参数 i 的格式不对",3);
				return false;
			}else{
				$v = new CKeyValidator();
				if($v -> isValid($msg['k'])){
					$r = $this -> model -> updatediyvar($msg[':i'],$msg['k'],$msg['v'],$msg['c']);
					if($r){
						$this -> view -> success("","更新成功",3);	
						return true;
					}else{
						$this -> view -> fail("",$this -> model -> getLastError(),10);	
						return false;
					}
				}else{
					$this -> view -> fail("","参数 k 的格式不对",3);
					return false;
				}
				
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;	
		}
	}
	private function setting_updateallowcomment($msg) {
		if(isset($msg['c']) ){
			if($msg['c'] !== 'on' && $msg['c'] !== 'off'){
				$this -> view -> fail("","参数不对",3);
				return;	
			}
			$r = $this -> model -> updateallowcomment($msg['c']);
			if($r){
				$this -> view -> success("","操作成功",3);	
				return true;
			}else{
				$this -> view -> fail("",$this -> getLastError(),5);
				return false;	
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;
		}
	}

	private function setting_updateallowrelativeartical($msg) {
		if(isset($msg['c']) ){
			if($msg['c'] !== 'on' && $msg['c'] !== 'off'){
				$this -> view -> fail("","参数不对",3);
				return;	
			}
			$r = $this -> model -> updateallowrelativeartical($msg['c']);
			if($r){
				$this -> view -> success("","操作成功",3);	
				return true;
			}else{
				$this -> view -> fail("",$this -> getLastError(),5);
				return false;	
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;
		}
	}
	private function setting_updateallowautopop($msg) {
		if(isset($msg['c']) ){
			if($msg['c'] !== 'on' && $msg['c'] !== 'off'){
				$this -> view -> fail("","参数不对",3);
				return;	
			}
			$r = $this -> model -> updateallowautopop($msg['c']);
			if($r){
				$this -> view -> success("","操作成功",3);	
				return true;
			}else{
				$this -> view -> fail("",$this -> getLastError(),5);
				return false;	
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;
		}
	}
	private function setting_superpassword($msg) {
		if(isset($msg['c']) ){
			$r = $this -> model -> setsuperpassword($msg['c']);
			if($r){
				$this -> view -> success("","操作成功",3);	
				return true;
			}else{
				$this -> view -> fail("",$this -> getLastError(),5);
				return false;	
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;
		}
	}
	private function setting_setRemindEmail($msg) {
		if(isset($msg['c']) ){
			$r = $this -> model -> setRemindEmail($msg['c'],$msg['v']);
			if($r){
				$this -> view -> success("","操作成功",3);	
				return true;
			}else{
				$this -> view -> fail("",$this -> getLastError(),5);
				return false;	
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;
		}
	}
	private function setting_setRemindSms($msg) {
		if(isset($msg['c']) ){
			$r = $this -> model -> setRemindSms($msg['c'],$msg['v'],$msg['x']);
			if($r){
				$this -> view -> success("","操作成功",3);	
				return true;
			}else{
				$this -> view -> fail("",$this -> getLastError(),5);
				return false;	
			}
		}else{
			$this -> view -> fail("","参数的个数不对",3);
			return;
		}
	}
	public function reviseTemplateLocation($msg){
		$r = $this -> model -> listTemplateFields();
		$x = '';
		foreach ($r as $val) {
		 	if($val['Field'] === 'location'){
		 		$x = $val['Type'];
		 		break;
		 	}
		} 
		$bodyhtml = $this -> _edit_template_location($x);
		$this -> view -> reviseTemplateLocation($bodyhtml);
	}
	public function reviseTemplateLocationDo($msg){
		$r = $this -> model -> reviseTemplateLocationDo($msg);
		if($r){
			$this -> view -> success('','更新成功...',3);
		}else{
			$this -> view -> fail('',$this -> model -> getLastError(),20);
		}
	}
//---------------------------------------------------------------------------------------------------------------------
	private function _get_priv_mask($tabname){
		static $priv = null;
		if(is_null($priv)){
			$priv = require_once(MODELROOT.'privilege/config/priv.config.php');
		}
		$role = R("USER");
		$role = $role["priv"];
		return $priv[$role][$tabname];
	}
	private function _get_config($tbname,$action){
		static $config = array();
		static $extraFields = false;
		if(!array_key_exists($tbname,$config)){
			if(file_exists(MODELROOT.'privilege/config/table.'.$tbname.'.config.php')){
				$config[$tbname] = require_once(MODELROOT.'privilege/config/table.'.$tbname.'.config.php');
			}else{
				$config[$tbname] = array();
			}
			if(!$extraFields){
				$extraFields = require_once(MODELROOT.'privilege/config/extraFields.php');
			}
			
		}
		$role = R("USER");
		$role = $role["priv"];
		$part_a =  isset($config[$tbname][$action]) ? $config[$tbname][$action] : array();
		$part_b = isset($extraFields[$role][$tbname]) ? $extraFields[$role][$tbname] : false;
		if(!is_array($part_a))$part_a = array();
		if($part_b){
			$part_a['extrafields'] = $part_b;
		}
		return($part_a);
	}
	private function _pks2towhere($msg,$pkname){
		$pks = $msg[':pk'];
		$ret = array();
		$pks = explode(",",$pks);
		$v = new CIntegerValidator();
		foreach($pks as $pk){
			if($v -> isValid($pk)){
				$ret[] = "`".$pkname."` = ".$pk;	
			}	
		}
		return join(" or ",$ret);
	}	
	
//***********************************************************************************
	private function _resynchronic_artical_channel(){
		return $this -> model -> resynchronic_artical_channel();	
	}
	private function _edit_template_location($template_name){
		$html = '
			<div style="width:420px;margin:50px auto;">
			<h2>管理首页模板位置</h2>
		';
		$html .= '<form method="post" action="/privilege/reviseTemplateLocationDo">';
		$html .= '<input style="width:420px;" name="txt" value="'.$template_name.'">';
		$html.= '<br><br><input type="submit" value="开始更新">';
		$html.='</form>
		<br>
		<br>
		</div>
	
		';
		return $html;
	}	
}