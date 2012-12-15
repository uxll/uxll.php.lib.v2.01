<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-14 
 *  @author: uxll@qq.com
 *  @file: view.php
 */
class privilegeView extends CView{
	private $engine;
	private $error = 0;
	private $currentTheme;
	private $modelHome;
	public function getLastError(){
		return $this -> error;
	}
	public function __construct($CurrentTheme){
		$this -> currentTheme = $CurrentTheme;
		$this -> engine = $this -> getEngine();
		$this->modelHome = $this->getThisHome();
	}
	public function welcome(){
		$ui = new privilegeUI(
			'',
			$this -> _index_html(),
			""
		);
		$this -> _display($ui);
	}
	public function upgrade(){
		$ui = new privilegeUI(
			'',
			$this -> _upgrade_html(),
			""
		);
		$this -> _display($ui);
	}
	public function resynchronicUI($data){
		$html = "";
		$this -> engine -> assign('resynchronicurl',$data['resynchronicurl']);
		$this -> engine -> assign('connectkey',$data['connectkey']);
		$html = $this -> engine -> fetch(MODELROOT."privilege/template/resynchronicarticalUI.tpl");
		$ui = new privilegeUI(
			'',
			$html,
			""
		);
		$this -> _display($ui);
	}
	public function resynchronicDoUI(){
		$ui = new privilegeUI(
			'',
			file_get_contents(MODELROOT."privilege/template/resynchronicingUI.tpl"),
			""
		);
		$this -> _display($ui);
	}
############################################################
####channel
############################################################
	
	public function channel($bodyhtml){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function addchannel($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_add_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function editchannel($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_edit_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function viewchannel($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_view_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
############################################################
####artical
############################################################
	public function artical($bodyhtml){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function addartical($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_add_header()
			.$this -> _get_add_header_htmleditor(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function editartical($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_edit_header()
			.$this -> _get_edit_header_htmleditor()
			,
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function viewartical($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_view_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
############################################################
####keywords
############################################################
	public function keywords($bodyhtml){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function addkeywords($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_add_header()
			.$this -> _get_add_header_htmleditor(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function editkeywords($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_edit_header()
			.$this -> _get_edit_header_htmleditor()
			,
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function viewkeywords($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_view_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}	
############################################################
####comment
############################################################
	public function comment($bodyhtml){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function addcomment($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_add_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function editcomment($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_edit_header()	,
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function viewcomment($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_view_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}	
############################################################
####feedback
############################################################
	public function feedback($bodyhtml){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function addfeedback($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_add_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function editfeedback($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_edit_header()	,
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function viewfeedback($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_view_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}	
############################################################	
###upload
############################################################	

	public function upload($action=""){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs(),
			$this -> _upload_ui($action),
			""
		);
		$this -> _display($ui);	
	}
	public function viewupload() {
		$imgs = '<div class="viewuploadimage">
		对选中项进行: 
		<a href="javascript:void(0)" id="viewupload-reverse">反选</a> |
		<a href="javascript:void(0)" id="viewupload-remove">删除</a> |
		<a href="javascript:void(0)" id="viewupload-replace">替换(暂时不支持批量替换)</a> |
		<a href="javascript:void(0)" id="viewupload-geturl">获取地址</a> |
		<a href="/privilege/upload">上传新的图片</a> 
		<br>
		<br>
		<div class="geturl"></div><div class="clearfix">';
		if ($handle = opendir(ROOT.UXLL_UPLOAD_IMAGES_PATH)) {
			while (false !== ($file = readdir($handle))) {
				if ($file != "." && $file != "..") {
					$imgs .= "<div class='picitem'>
						<img src='".HOME.UXLL_UPLOAD_IMAGES_PATH.$file."' height='64'>
						<br><input type='checkbox' value='".HOME.UXLL_UPLOAD_IMAGES_PATH.$file."'>
						</div>
						";
				}
			}
			closedir($handle);
		}
		$imgs .= "</div></div>";
		$ui = new privilegeUI(
			$this -> _get_display_headerjs()
			.$this -> _get_viewimagejs()
			,
			$imgs,
			""
		);
		$this -> _display($ui);	
	}
	private function _upload_ui($action){
		$html = '<div style="width:565px;margin:50px auto;">';
		$html .= '<fieldset><legend>'.($action === '' ? '上传图片' : '替换图片').'</legend>';
		$html .= '<form enctype="multipart/form-data" method="post" action="'.($action === "" ?"/privilege/uploadimg" : $action).'">';
		$html .= '选择上传的文件,相同的文件覆盖请养成<br/>命名文件名时带上日期这个习惯:<br/>(只能上传图片,上传后文件路径为<font color=blue>/dc/uploads/现在的文件名</font>)
		<br>
		
		'.($action ? $this -> _view_replaceimg($action) : '').'
		<br><font color="red">只能上传英文的文件名</font><br>
		<input name="txt" type="file">';
		$html.= '<br/>
		
				
				<br/><input type="submit" value="upload">
				<br>
				<br>
				<a href="/privilege/viewuploadedimage">查看已上传的图片</a>
		
		';
		$html.='</form></fieldset></div>';
		return $html;		
		
	}	
	private function _view_replaceimg($i) {
		$i = explode("i=",$i);
		$i = $i[1];
		return "<div class='replaceimg'><img src='/".UXLL_UPLOAD_IMAGES_PATH.$i."' height=50><br>".$i."</div>";
	}	
############################################################	
###passworld
############################################################		
	public function systemuser($bodyhtml){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function addsystemuser($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_add_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function editsystemuser($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_edit_header()	,
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function viewsystemuser($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_view_header(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}	
############################################################	
###template
############################################################		
	public function template($msg){
		$_t = array(
			"skeleton" => "整个框架模板"
			,"head" => "头部模板"
			,"bottom" => "底部模板"
			,"index" => "首页模板"
			,"list" => "列表模板"
			,"artical" => "文章模板"
			,"artical-head" => "文章头部模板"
			,"artical-bottom" => "文章尾部模板"
			,"css" => "修改 /themes/ui/css/".$msg[':n'].".css 文件内容"
		);
		$this -> engine -> assign('tpl',$msg[':p']);
		$this -> engine -> assign('name',isset($msg[':n']) ? $msg[':n'] : '');
		$this -> engine -> assign('title',$_t[$msg[':p']]);
		$this -> engine -> assign('var',
			$msg[':p'] === 'css' ?
			CCache::read(THEMEROOT.'ui/css/'.$msg[':n'].'.css')
			:
			CCache::read(THEMEROOT.'template/'.$msg[':p'].'.htm')
		);
		$bodyhtml = $this -> engine -> fetch(MODELROOT."privilege/template/template.editor.tpl");
		$ui = new privilegeUI(
			$this -> _get_display_headerjs()
			.$this -> _get_template_savejs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function css($msg){
		$ui = new privilegeUI(
			$this -> _get_display_headerjs()
			.$this -> _get_template_savejs(),
			$this -> _cssUI(),
			""
		);
		$this -> _display($ui);
	}
	private function _cssUI(){
		return $this -> engine -> fetch(MODELROOT."privilege/template/submitCssForm.tpl");
	}
############################################################	
###setting
############################################################		
	public function setting($settingData){
		$settingData['currentTheme'] = $this -> currentTheme;
		$this -> engine -> assign('setting',$settingData);

		$bodyhtml = $this -> engine -> fetch(MODELROOT."privilege/template/setting.tpl");
		$ui = new privilegeUI(
			$this -> _get_display_headerjs()
			.$this -> _get_mypreference(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
	public function reviseTemplateLocation($bodyhtml) {
		$ui = new privilegeUI(
			$this -> _get_display_headerjs()
			.$this -> _get_template_savejs(),
			$bodyhtml,
			""
		);
		$this -> _display($ui);
	}
//--------------------------------------------------------------------------------------------------------------
	private function _get_display_headerjs(){
		return '
			<script type="text/javascript" src="'.$this->getThisHome().'template/js/initDisplayUI.js"></script>
			<script type="text/javascript" src="'.$this->getThisHome().'template/js/keymap.js"></script>
		';
	}
	private function _get_add_header(){
		return '
			<link type="text/css" rel="stylesheet" href="'.$this->getThisHome().'template/css/addform.css">
		';
	}
	private function _get_edit_header(){
		return '
			<link type="text/css" rel="stylesheet" href="'.$this->getThisHome().'template/css/editform.css">
		';
	}
	private function _get_edit_header_htmleditor(){
		return '
			<script type="text/javascript" src="'.COMMONUIHOME.'ueditor/editor_config.js"></script>
			<script type="text/javascript" src="'.COMMONUIHOME.'ueditor/uxll.ueditor.config.js"></script>
			<script type="text/javascript" src="'.COMMONUIHOME.'ueditor/editor.min.js"></script>
			<script type="text/javascript" src="'.COMMONUIHOME.'ckeditor/ckeditor.js"></script>
			<script type="text/javascript" src="'.$this->getThisHome().'template/js/start-ueditor.js"></script>
			<link type="text/css" rel="stylesheet" href="'.COMMONUIHOME.'ueditor/themes/default/ueditor.css">
		';
	}
	private function _get_viewimagejs(){
		return '
			<script type="text/javascript" src="'.$this->getThisHome().'template/js/viewimage.js"></script>
			<link type="text/css" rel="stylesheet" href="'.$this->getThisHome().'template/css/viewimage.css">
		';
	}
	private function _get_template_savejs(){
		return '
			<script type="text/javascript" src="'.$this->getThisHome().'template/js/save-template.js"></script>
		';
	}
	private function _get_add_header_htmleditor(){
		return $this -> _get_edit_header_htmleditor();
	}
	private function _get_view_header(){
		return '
			<link type="text/css" rel="stylesheet" href="'.$this->getThisHome().'template/css/viewform.css">
		';
	}
	private function _get_mypreference(){
		return '
			<link type="text/css" rel="stylesheet" href="'.$this->getThisHome().'template/css/mypreference.css">
		';
	}
	private function _index_html(){
		ob_start();
		require(MODELROOT."privilege/template/privielge.home.php");
		$x = ob_get_contents();
		ob_end_clean();
		return $x;
			
	}
	private function _upgrade_html(){
		ob_start();
		require(MODELROOT."privilege/template/upgradeUI.php");
		$x = ob_get_contents();
		ob_end_clean();
		return $x;
	}
	private function _resynchronicarticalUIWrap($x){
		return "<div style='margin:50px auto;width:500px;'>".$x."</div>";
	}
	private function _display($ui) {
		$ui -> setCurrentTheme($this -> currentTheme);
		$ui -> setPrivilege(R("USER"));
		$ui->setThisHome($this->modelHome);
		$this -> engine -> assign('ui',$ui);
		$this -> engine -> display(MODELROOT."privilege/template/skeleton.tpl");
	}	
}