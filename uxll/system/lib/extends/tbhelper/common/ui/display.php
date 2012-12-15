<?php
class tbhelperDisplayUI{
	private $caption = null;
	private $url = array();
	private $fieldsmanifest = null;
	private $extrafields = null;
	private $fieldsdisplayname = null;
	private $extrahtml = null;
	private $tbname = null;
	private $thead = null;
	private $privmask = null;
	private $viewmask = null;
	private $engine;
	private $qs="";
	
	private $tbdescription;
	public function __construct($tbhelperBase){
		$this -> engine = $tbhelperBase;
		$this -> tbdescription = $this -> engine -> getTbDescripton();
		$dsc = array();
		foreach($this -> tbdescription as $val){
			$dsc[$val['Field']] = $val;
		}
		$this -> tbdescription = $dsc;
	}
	
	public function setTbname($v){
		$this -> tbname = $v;
	}
	public function setCaption($v){
		$this -> caption = $v;
	}
	public function getCaption(){
		return $this -> caption;
	}
	public function seturl($v){
		$this -> url = $v;
	}
	public function geturl(){
		$url = $this -> url;
		foreach($url as  &$val){
			$val = R("HTTP") -> setMessage("action",$val);	
		}
		return $url;
	}	


	public function setFieldsManifest($v){
		$this -> fieldsmanifest = $v;
	}
	public function getFieldsManifest(){
		return $this -> fieldsmanifest;
	}
	public function setExtraFields($v){
		$this -> extrafields = $v;
	}
	public function getExtraFields(){
		return $this -> extrafields;
	}
	public function setFieldsDisplayName($v){
		$this -> fieldsdisplayname = $v;
	}
	public function getFieldsDisplayName(){
		return $this -> fieldsdisplayname;
	}
	public function setExtraHTML($v){
		$this -> extrahtml = $v;
	}
	public function getExtraHTML(){
		return $this -> extrahtml;
	}
	public function setPrivMask($v){
		$this -> privmask = $v;
	}
	public function getPrivMask(){
		#echo P($this -> privmask);
		return $this -> privmask;
	}
	public function setViewMask($v){
		$this -> viewmask = $v;	
	}
	public function getViewMask() {
		return $this -> viewmask;
	}
	
	
	public function setRsBody($rsbody){
		$this -> rsbody = $rsbody;
	}
	public function setAllFieldsConfig($config){
		$this -> allconfig = $config;
	}
	public function setManifestMask($manifest_fields){
		//哪些字段需要用自定义函数来显示
		$this -> manifestMask = $manifest_fields;
	}
	public function setExtraMask($extra_fields){
		//最后一列的操作以这个为准
		$this -> extraMask = $extra_fields;
	}
	public function setDisplayMask($display_fields){
		//显示哪些列以这个为准
		$this -> displayMask = $display_fields;
	}
	public function setPageHTML($pagehtml){
		$this -> pagehtml = $pagehtml;	
	}
	
	
	public function getRsBody(){
		return $this -> rsbody;
	}
	public function getPageHTML(){
		return $this -> pagehtml;	
	}
	public function getAllFieldsConfig(){
		return $this -> allconfig;
	}
	public function getManifestMask(){
		return $this -> manifestMask;
	}
	public function getExtraMask(){
		return $this -> extraMask;
	}
	public function getDisplayMask(){
		return $this -> displayMask;
	}	
	
	
	
	public function displayExtraFields($row,$extramask,$extraconfig){
		//第一个参数是表内容
		//第二个参数是参考标准，是表中存在的字段
		//配置文件的函数，TEXT，字段
		#echo P($row);	
		#echo P($extramask);	
		#echo P($extraconfig);	
		$html = '';
		for($i=0,$c = count($extraconfig['function']);$i<$c;$i++){
			$cls = explode("::",$extraconfig['function'][$i]);
			if(count($cls) !== 2){
				return "error:format cls::method";	
			}
			if(!class_exists($cls[0])){
				return "error:no cls ".$cls[0];	
			}
			$rc = new ReflectionClass($cls[0]);
			if (!$rc -> implementsInterface('IuserFunction')){
				return "error:class must be implements IuserFunction";
			}
			$argc_row = array();
			foreach($extraconfig['mask'][$i] as $arv){
				if(!in_array($arv,$extramask)){
					return $arv."字段不存在";
				}
				$argc_row[$arv] = $row[$arv];
			}
			$controller = $rc -> newInstance();
			$method = $rc -> getMethod('execute');			
			$html .= $method -> invokeArgs($controller, array($argc_row,$cls[1],$extraconfig['text'][$i]));
		}
		return $html;		
	}
	
	public function displayManifestFields($field,$row){
		$manifest_fields = $this -> getFieldsManifest();
		return $this -> engine -> tbManifest -> execute($manifest_fields[$field],$row,$field);
	}
	public function displayDefaultFields($field,$row){
		$defaultvalue = $row[$field];
		$pk = $this -> engine -> getPrimaryKey();
		$type = $this -> engine -> getFieldType($field);
		$extraData = $this -> getFieldsExtraData($field,$type,$pk,$defaultvalue);
		return $this -> engine -> getHTML('display',$type,$extraData);
	}
	public function setLastQueryString($qs) {
		$this -> qs = $qs;
	}
	public function getLastQueryString() {
		return $this -> qs;
	}
//--------------------------------------------------------------
	public function getColspan(){
		$row = count($this -> fieldsdisplayname);
		if($this -> extrafields)$row++;
		return $row;
	}	
	public function getPrivMaskText(){
		return array(
			"add" => "添加(A) "
			,"append" => "添加(A)"
			,"edit" => "编辑(E) "
			,"update" => "更新(U) "
			,"display" => "列表(L)"
			,"view" => "查看(V) "
			,"remove" => "删除(D) "
			,"search" => "查询(S)"
			
		);	
	}
	public function getPrimaryKey() {
		return $this -> engine -> getPrimaryKey();
	}
	public function debug($var){
//		var_dump($var);
//		die();
	}
//---------------------------------------------------------------------------------------------------
	private function getEnumByDescription($subject) {
		if(preg_match("/^[a-z]+\(('[\w-]+'(,'[\w-]+')*)\)$/", $subject, $matches)){
			return explode("','",substr($matches[1], 1,-1));
		}
		return array();
	}
	private function getFieldsExtraData($field,$type,$pk,$default=null) {
		$extraData = array();
		$type = $this -> engine -> getType($type);

		switch($type){
			case "textarea":
				$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
			case "datetime":
				$extraData['defaultvalue'] = is_null($default) ? date('Y-m-d H:i:s',time() + 8*60*60) : $default;
				break;
			case "date":
				$extraData['defaultvalue'] = is_null($default) ? date('Y-m-d',time() + 8*60*60) : $default;
				break;
			
			case "enum":
			case "set":
				$fieldsmanifest = $this -> getFieldsManifest();
				$description = $this -> getViewMask();//
				$description_edit = $this -> getViewMask();//
				
				$value = null;
				$enum = null;
				if(is_array($fieldsmanifest) && array_key_exists($field,$fieldsmanifest)){
					if(array_key_exists('value',$fieldsmanifest[$field])){
						$value = $fieldsmanifest[$field]['value'];
					}
					if(array_key_exists('enum',$fieldsmanifest[$field])){
						$enum = $fieldsmanifest[$field]['enum'];
					}
				}
				if(is_null($enum)){
					$enum = $this -> getEnumByDescription(
						$description_edit
						[$field]["Type"]
					);
				}
				
				if(is_null($value)){
					$value = $enum;
				}
				$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				$extraData['enum'] = $enum;
				$extraData['value'] = $value;
				
				break;
			case "varbinary":
				$extraData['width'] = 30;
				$extraData['height'] = 30;
				$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
			case "text":
				$extraData['defaultvalue'] = is_null($default) ? '' : $default;
				break;
		}
		return $extraData;
	}	
}