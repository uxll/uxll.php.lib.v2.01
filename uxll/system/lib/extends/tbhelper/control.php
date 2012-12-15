<?php
//UXLL_TBHELPER_ROOT
class tbhelper{
	private $error = 0;
	
	protected $model;
	protected $view;
	
	private $config;
	private $tbname = null;
	private $base;
	private $_last_search_query_string = "tbhelper-last-query-string";
	
	
	private $fieldsTypeUI;
	private $tbDescription;
	
	private $___temp_result_manifest = array();
//---------common func---------------------------	
	public function getLastError(){
		return $this -> error;
	}	
	static public function L($key,$replacement=null){
		static $lang = null;
		if(is_null($lang)){
			$lang = require_once(UXLL_TBHELPER_ROOT.'language/cn.php');
		}
		return L($key,$lang,$replacement);
	}
//---------------------------------------------------
	
	public function __construct($tbname){
		require_once(UXLL_TBHELPER_ROOT.'view.php');	
		require_once(UXLL_TBHELPER_ROOT.'model.php');	

		require_once(UXLL_TBHELPER_ROOT.'common/tbPrivMask.php');
		require_once(UXLL_TBHELPER_ROOT.'common/tbhelperBase.php');
		require_once(UXLL_TBHELPER_ROOT.'common/tbUI_userFunc.php');//处理字符串 cls::method|text|field0,field1,...;repeat...
		require_once(UXLL_TBHELPER_ROOT.'common/tbInitializeConfig.php');//interface
		
		$this -> tbname = $tbname;
		$this -> view = new tbhelperView();
		$this -> model = new tbhelperModel();
		$this -> base = new tbhelperBase($this -> tbname);
		$this -> mask = new tbPrivMask();

		
		
	}


	
	public function add($config = array(),$privmask = array(),$extraConfig = array("skipPk" => true)){
		//config 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\add.txt
		//privmask 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\privmask.txt
		
		$skipPk = $extraConfig['skipPk'];
		require_once(UXLL_TBHELPER_ROOT.'common/tbAction/add.php');

		$ui = new tbAdd();
		$ui = $ui -> init($this -> tbname,$config,$this -> base);
		
		if(!$ui -> getCaption()){
			$ui -> setCaption($this -> getAddFormTitle());
		}
		if(!$ui -> getAppendUrl()){
			$ui -> setAppendUrl(
				$this -> filterURL(
					$this -> geturl('append')
				)
			);
		}
		
		if(!$ui -> getFieldsDisplayName()){
			$ui -> setFieldsDisplayName($this -> getFieldsDisplayName());
		}else{
			$urlp = $ui -> getFieldsDisplayName();
			$urlc = $this -> getFieldsDisplayName();
		
			$urlret = array();
			foreach($urlc as $k => $val){
				if(array_key_exists($k,$urlp) && $urlp[$k]){
					#echo $urlp[$k];
					$urlret[$k] = $urlp[$k];
				}else{
					$urlret[$k] = $val;	
				}
			}
			#echo P($urlret);		
			$ui -> setFieldsDisplayName($urlret);
		}
		
		if(!$ui -> getSubmitBtnText()){
			$ui -> setSubmitBtnText("提交");
		}
		
		if(!$ui -> getMethod()){
			$ui -> setMethod("post");
		}
	
		
		if(!is_array($ui -> getFieldsManifest())){
			$ui -> setFieldsManifest(array());
		}

		$addmask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('add',$privmask) ? $privmask['add'] : 0
		);
		$appendmask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('append',$privmask) ? $privmask['append'] : 0
		);
		
		$add_fields = $this -> _filter_field($this -> mask -> getFields($addmask));
		$append_fields = $this -> _filter_field($this -> mask -> getFields($appendmask));
		
		$final_fields = array_unique(array_merge($append_fields,$add_fields));

		$__final__fields = array();
		if($skipPk){
			$pk = $this -> base -> getPrimaryKey();
			foreach($final_fields as $field){
				if($field != $pk)$__final__fields[] = $field;
			}
			$final_fields = $__final__fields;
		}
//echo P($appendmask);
//echo P($appendmask);
		
		$add_descriptons = array();
		foreach($addmask as $v){
			if(in_array($v['Field'],$add_fields)){
					$add_descriptons[$v['Field']] = $v;
			}
		}
		
		$append_descriptons = array();
		foreach($appendmask as $v){
			if(in_array($v['Field'],$append_fields)){
					$append_descriptons[$v['Field']] = $v;
			}
		}

		if(!$ui -> getExtraHTML()){
			$ui -> setExtraHTML('');
		}
		$ui -> setTbname($this -> tbname);
		$ui -> setAddDescription($add_descriptons);
		$ui -> setAppendDescription($append_descriptons);
		$ui -> setAllFields($final_fields);
//		$db = $this -> model -> table($this -> tbname)
//		-> fields($final_fields)
//		-> page(2,2)
//		;
		
		//echo P($ui -> getEnumByDescription("enum('yes','no','yy','go')"));	
		return $this -> view -> add($ui);

	}
	
	public function append($msg,$config = array(),$privmask = array(),$extraConfig = array("skipPk" => true)){
		//config 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\append.txt
		//privmask 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\privmask.txt
		require_once(UXLL_TBHELPER_ROOT.'common/tbAction/append.php');
		$skipPk = $extraConfig['skipPk'];
		
		$ui = new tbAppend();
		$ui = $ui -> init($this -> tbname,$config,$this -> base);
		
		if(!$ui -> getDatawrap()){
			$ui -> setDatawrap(array());
		}
		if(!$ui -> getPatterns()){
			$ui -> setPatterns(array());
		}
		if(!$ui -> getFieldsDisplayName()){
			$ui -> setFieldsDisplayName($this -> getFieldsDisplayName());
		}
		
		$appendmask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('append',$privmask) ? $privmask['append'] : 0
		);
		
		$append_fields = $this -> _filter_field($this -> mask -> getFields($appendmask));
		$__final__fields = array();
		if($skipPk){
			$pk = $this -> base -> getPrimaryKey();
			foreach($append_fields as $field){
				if($field != $pk)$__final__fields[] = $field;
			}
			$append_fields = $__final__fields;
		}
		
		$bc = $this -> baseCheckWithDescriptionForAppend($msg,$append_fields,$ui -> getFieldsDisplayName());
		if($bc){
			$this -> view -> fail("",$bc,20);
			return false;	
		}
		$this -> model -> table($this -> tbname);
		$r = $this -> model -> append($msg,$append_fields,$ui);
		if(!$r){
			$this -> error = $this -> model -> getLastError();
			return false;
		}else{
			return true;
		}
	}

	public function display($config = array(),$privmask = array(),$extConfig = array()){
		return $this -> _display($config ,$privmask,$extConfig,"display");
	}
	
	public function search($config = array(),$privmask = array(),$extConfig = array()){
		return $this -> _display($config ,$privmask,$extConfig,"search");
	}

	public function edit($where,$config = array(),$privmask = array()){
		//config 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\edit.txt
		//privmask 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\privmask.txt
		//不管WHERE的条件是什么，返回的RS中总带PK。然后再根据PK来更新数据
		require_once(UXLL_TBHELPER_ROOT.'common/tbAction/edit.php');

		$ui = new tbEdit();
		$ui = $ui -> init($this -> tbname,$config,$this -> base);
		
		if(!$ui -> getCaption()){
			$ui -> setCaption($this -> getEditFormTitle());
		}
		if(!$ui -> getUpdateUrl()){
			$ui -> setUpdateUrl(
				$this -> filterURL(
					$this -> geturl('update')
				)
			);
		}
		
		if(!$ui -> getFieldsDisplayName()){
			$ui -> setFieldsDisplayName($this -> getFieldsDisplayName());
		}else{
			$urlp = $ui -> getFieldsDisplayName();
			$urlc = $this -> getFieldsDisplayName();
		
			$urlret = array();
			foreach($urlc as $k => $val){
				if(array_key_exists($k,$urlp) && $urlp[$k]){
					#echo $urlp[$k];
					$urlret[$k] = $urlp[$k];
				}else{
					$urlret[$k] = $val;	
				}
			}
			#echo P($urlret);		
			$ui -> setFieldsDisplayName($urlret);
		}
		
		if(!$ui -> getSubmitBtnText()){
			$ui -> setSubmitBtnText("提交");
		}
		
		if(!$ui -> getMethod()){
			$ui -> setMethod("post");
		}
	
	
		if(!is_array($ui -> getFieldsManifest())){
			$ui -> setFieldsManifest(array());
		}

		$editmask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('edit',$privmask) ? $privmask['edit'] : 0
		);
		$Updatemask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('update',$privmask) ? $privmask['update'] : 0
		);
		
		$edit_fields = $this -> _filter_field($this -> mask -> getFields($editmask));
		$Update_fields = $this -> _filter_field($this -> mask -> getFields($Updatemask));
#echo P(array_key_exists('update',$privmask) ? $privmask['update'] : 0);		
		$final_fields = array_unique(array_merge($Update_fields,$edit_fields));
//unshift pk
		$pk = $this -> base -> getPrimaryKey();
		if(!in_array($pk,$final_fields)){
			array_unshift($final_fields,$pk);
		}

		$this -> model -> table($this -> tbname);
		$row = $this -> model -> getEditRow($final_fields,$where);
		
		$edit_descriptons = array();
		foreach($editmask as $v){
			if(in_array($v['Field'],$edit_fields)){
				$edit_descriptons[$v['Field']] = $v;
			}
		}
		
		$Update_descriptons = array();
		foreach($Updatemask as $v){
			if(in_array($v['Field'],$Update_fields)){
				$Update_descriptons[$v['Field']] = $v;
			}
		}

		if(!$ui -> getExtraHTML()){
			$ui -> setExtraHTML('');
		}
		$ui -> setTbname($this -> tbname);
		$ui -> setEditDescription($edit_descriptons);
		$ui -> setUpdateDescription($Update_descriptons);
		$ui -> setAllFields($final_fields);
		$ui -> setRows($row);

		
		//echo P($Update_descriptons);
		return $this -> view -> edit($ui);

	}


	public function update($msg,$config = array(),$privmask = array(),$extraConfig = array("skipPk" => true)){
		//config 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\append.txt
		//privmask 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\privmask.txt
		require_once(UXLL_TBHELPER_ROOT.'common/tbAction/update.php');
		$skipPk = $extraConfig['skipPk'];
#die(P($msg))		;		
		$ui = new tbUpdate();
		$ui = $ui -> init($this -> tbname,$config,$this -> base);
		
		if(!$ui -> getDatawrap()){
			$ui -> setDatawrap(array());
		}
		if(!$ui -> getPatterns()){
			$ui -> setPatterns(array());
		}
		if(!$ui -> getFieldsDisplayName()){
			$ui -> setFieldsDisplayName($this -> getFieldsDisplayName());
		}
		
		$updatemask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('update',$privmask) ? $privmask['update'] : 0
		);
		
		$updatemask = $this -> _filter_field($this -> mask -> getFields($updatemask));
		$__final__fields = array();
		if($skipPk){
			$pk = $this -> base -> getPrimaryKey();
			foreach($updatemask as $field){
				if($field != $pk)$__final__fields[] = $field;
			}
			$updatemask = $__final__fields;
		}
	
		$bc = $this -> baseCheckWithDescription($msg,$updatemask,$ui -> getFieldsDisplayName());
		if($bc){
			$this -> view -> fail("",$bc,20);
			return false;	
		}

		$__final__fields = array();
		foreach($updatemask as $field){
			$__final__fields[$field] = $this -> base -> getFieldType($field);
		}
		$updatemask = $__final__fields;

		
		$this -> model -> table($this -> tbname);
		$r = $this -> model -> update($msg,$updatemask,$ui);
		if(!$r){
			$this -> error = $this -> model -> getLastError();
			return false;
		}else{
			return true;
		}
	}


	public function remove($where,$privmask = array()){
		
		if(array_key_exists('remove',$privmask)){
			$p = !!$privmask['remove'];
		}else{
			$p = false;	
		}
		if($p === false){
			$this -> view -> fail('',tbhelper::L('permission deny'));	
			return false;
		}
		$this -> model -> table($this -> tbname);

		$r = $this -> model -> remove($where);
		if($r){
			return true;
		}else{
			$this -> error = $this -> model -> getLastError();
			return false;
		}
	}


	public function view($where,$config = array(),$privmask = array()){
		//config 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\edit.txt
		//privmask 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\privmask.txt
		require_once(UXLL_TBHELPER_ROOT.'common/tbAction/view.php');

		$ui = new tbView();
		$ui = $ui -> init($this -> tbname,$config,$this -> base);
		
		if(!$ui -> getCaption()){
			$ui -> setCaption($this -> getDisplayTitle());
		}
		
		if(!$ui -> getFieldsDisplayName()){
			$ui -> setFieldsDisplayName($this -> getFieldsDisplayName());
		}else{
			$urlp = $ui -> getFieldsDisplayName();
			$urlc = $this -> getFieldsDisplayName();
		
			$urlret = array();
			foreach($urlc as $k => $val){
				if(array_key_exists($k,$urlp) && $urlp[$k]){
					#echo $urlp[$k];
					$urlret[$k] = $urlp[$k];
				}else{
					$urlret[$k] = $val;	
				}
			}
			#echo P($urlret);		
			$ui -> setFieldsDisplayName($urlret);
		}
		
		if(!is_array($ui -> getFieldsManifest())){
			$ui -> setFieldsManifest(array());
		}

		$viewmask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('view',$privmask) ? $privmask['view'] : 0
		);
		
		$view_fields = $this -> _filter_field($this -> mask -> getFields($viewmask));
		
		$final_fields = array_unique($view_fields);
//unshift pk
		$pk = $this -> base -> getPrimaryKey();
		if(!in_array($pk,$final_fields)){
			array_unshift($final_fields,$pk);
		}

		$this -> model -> table($this -> tbname);
		$row = $this -> model -> getEditRow($final_fields,$where);
		
		$view_descriptons = array();
		foreach($viewmask as $v){
			if(in_array($v['Field'],$final_fields)){
				$view_descriptons[$v['Field']] = $v;
			}
		}
		


		if(!$ui -> getExtraHTML()){
			$ui -> setExtraHTML('');
		}
		$ui -> setTbname($this -> tbname);
		$ui -> setViewDescription($view_descriptons);
		$ui -> setAllFields($final_fields);
		$ui -> setRows($row);

		
		//echo P($row);
		return $this -> view -> view($ui);

	}



//------------------------------------------------------------------------------------------------------------------
	private function baseCheckWithDescription($msg,$fields,$displaynames) {
				
		$pks = $msg[$this -> base -> getPrimaryKey()];
		foreach($pks as $pk){
			foreach($fields as $field){
				$type = $this -> base -> getFieldType($field);
				if(!$this -> base -> getFieldFlag($field) && !isset($msg[$field][$pk])){
					if($type === "set"){
						
					}else{
						return $displaynames[$field][$pk].' '.tbhelper::L("is empty");	
					}
					
				}
				$type = $this -> base -> getFieldType($field);
				if(
					$type === 'int' || 
					$type === 'mediumint' || 
					$type === 'tinyint' || 
					$type === 'decimal' || 
					$type === 'bigint' || 
					$type === 'smallint'
					
				){
					$p = new CIntegerValidator();
					if(!$p -> isValid($msg[$field][$pk])){
						if(!$this -> base -> getFieldFlag($field)){
							#die(P($this-> base -> getPrimaryKey()));
							if($this-> base -> getPrimaryKey() !== $field)
							return $displaynames[$field][$pk].' '.tbhelper::L("is not an Integer");
						}
					}
				}else if(
					$type === 'float' || 
					$type === 'double'
				
				){
					$p = new CNumberValidator();
					if(!$p -> isValid($msg[$field][$pk])){
						if(!$this -> base -> getFieldFlag($field))
						return $displaynames[$field][$pk].' '.tbhelper::L("is not an number");
					}
				}
			}
		}
			
		return '';
	}
	private function baseCheckWithDescriptionForAppend($msg,$fields,$displaynames) {

		foreach($fields as $field){
			$type = $this -> base -> getFieldType($field);
			if(
			!$this -> base -> getFieldFlag($field) && 
			(!isset($msg[$field]) || (isset($msg[$field]) && $msg[$field] === ''))){
				if($type === "set"){
					
				}else{
					return "<b>".$displaynames[$field].'</b> '.tbhelper::L("is empty");	
				}
				
			}
			$type = $this -> base -> getFieldType($field);
			if(
				$type === 'int' || 
				$type === 'mediumint' || 
				$type === 'tinyint' || 
				$type === 'decimal' || 
				$type === 'bigint' || 
				$type === 'smallint'
				
			){
				$p = new CIntegerValidator();
				if(!$p -> isValid($msg[$field])){
					if(!$this -> base -> getFieldFlag($field)){
						#die(P($this-> base -> getPrimaryKey()));
						if($this-> base -> getPrimaryKey() !== $field)
						return $displaynames[$field].' '.tbhelper::L("is not an Integer");
					}
				}
			}else if(
				$type === 'float' || 
				$type === 'double'
			
			){
				$p = new CNumberValidator();
				if(!$p -> isValid($msg[$field])){
					if(!$this -> base -> getFieldFlag($field))
					return $displaynames[$field].' '.tbhelper::L("is not an number");
				}
			}
		}
		return '';
	}






//-----------------------------------------------------------------------------------------------------------------
	private function _calc_36orderby2binary($orderby_36,$fields){
		$orderby = base_convert($orderby_36,36,3);
		$orderby = @str_repeat("0",count($fields)-strlen($orderby)).$orderby;
		$fields_orderby = array();
		for($i=0;$i<count($fields);$i++){
			switch($orderby{$i}){
				case '1':
					$fields_orderby[] = '`'.$fields[$i].'` desc';
					break;
				case '2':
					$fields_orderby[] = '`'.$fields[$i].'` asc';
					break;
			}	
		}
		$orderby = join(',',$fields_orderby);
		return $orderby;		
	}	
	private function _fill_display_ext_config($extConfig){
		$displayNum = array_key_exists("displayNum",$extConfig) ? $extConfig["displayNum"] : 5;
		$countOf = array_key_exists("countOf",$extConfig) ? $extConfig["countOf"] : 10;
		
		
		$orderby = array_key_exists("order",$extConfig) ? $extConfig["order"] : -1;

		if($orderby === -1){
			$orderby = "`".$this -> base -> getPrimaryKey()."` desc"	;
		}else{
			$orderby = $this -> _calc_36orderby2binary($orderby,$final_fields);
		}	
		return array(
			"displayNum" => $displayNum
			,"countOf" => $countOf
			,"orderby" => $orderby
		);	
	}
	private function _display($config = array(),$privmask = array(),$extConfig = array(),$mode = "display"){
		//config 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\display.txt
		//privmask 格式在 D:\web\htdocs\lib\uxll\extends\tbhelperEx\help\privmask.txt
		require_once(UXLL_TBHELPER_ROOT.'common/tbAction/display.php');

		$ui = new tbDisplay();
		$ui = $ui -> init($this -> tbname,$config,$this -> base);
		
		if(!$ui -> getCaption()){
			$ui -> setCaption($this -> getDisplayTitle());
		}
		if(!$ui -> geturl()){
			$ui -> seturl($this -> geturl());
		}else{
			//如果只配置了一部分其它的还要用默认填充
			$urlp = $ui -> geturl();
			$urlc = $this -> geturl();
			$urlret = array();
			foreach($urlc as $k => $val){
				if(array_key_exists($k,$urlp) && $urlp[$k]){
					$urlret[$k] = $urlp[$k];
				}else{
					$urlret[$k] = $val;	
				}
			}	
			$ui -> seturl($urlret);
		}
		
		
		if(!$ui -> getFieldsDisplayName()){
			$ui -> setFieldsDisplayName($this -> getFieldsDisplayName());
		}else{
			$urlp = $ui -> getFieldsDisplayName();
			$urlc = $this -> getFieldsDisplayName();
		
			$urlret = array();
			foreach($urlc as $k => $val){
				if(array_key_exists($k,$urlp) && $urlp[$k]){
					#echo $urlp[$k];
					$urlret[$k] = $urlp[$k];
				}else{
					$urlret[$k] = $val;	
				}
			}
			#echo P($urlret);		
			$ui -> setFieldsDisplayName($urlret);
		}

	
		
		if(!is_array($ui -> getFieldsManifest())){
			$ui -> setFieldsManifest(array());
		}

		$displaymask = $this -> mask -> getMaskedDescripton(
			$this -> tbname,
			array_key_exists('display',$privmask) ? $privmask['display'] : 0
		);
		
	
		if(!$ui -> getExtraFields()){
			$ui -> setExtraFields(null);
		}
		if(!$ui -> getExtraHTML()){
			$ui -> setExtraHTML('');
		}
		$ui -> setTbname($this -> tbname);
		$ui -> setPrivMask($this -> getPriv($privmask));
		
	
		//计算FieldsManifest和ExtraFields的FIELDS并集
		$all_fields_display_config = array();

		if($this -> _getAllFieldsFromFieldManifest($ui -> getFieldsManifest())){
			//echo P($this -> ___temp_result_manifest);
			//die();
			$manifest_fields = $this -> calc_union($this -> ___temp_result_manifest['mask']);	
			$all_fields_display_config['manifest'] = $this -> ___temp_result_manifest;
		
		}else{
			$this -> view -> fail("",$this -> error,100);	
			return false;
		}
		
		
		if($ui -> getExtraFields() && $this -> _getAllFieldsFromExtraFields($ui -> getExtraFields())){
			$extra_fields = $this -> calc_union($this -> ___temp_result_manifest['mask']);	
			$all_fields_display_config['extra'] = $this -> ___temp_result_manifest;
		}else{
			if(!$ui -> getExtraFields()){
				$extra_fields = array();	
				$all_fields_display_config['extra'] = array(
					"function" => array()
					,"text" => array()
					,"mask" => array()
				);
			}else{
				$this -> view -> fail("",$this -> error,100);	
				return false;				
			}

		}
		$display_fields = $this -> _getAllFieldsFromDisplayMask(array_key_exists("display",$privmask) ? $privmask['display'] : 0);
		if(!$display_fields){
			$this -> view -> fail("",$this -> error,100);	
			return false;
		}
	

		
		$manifest_fields = $this -> _filter_field($manifest_fields);
		$extra_fields = $this -> _filter_field($extra_fields);
		$display_fields = $this -> _filter_field($display_fields);
		
		$final_fields = array_unique(array_merge($display_fields,$extra_fields,$manifest_fields));
		$where = array_key_exists("where",$extConfig) ? $extConfig["where"] : '';
		$extConfig = $this -> _fill_display_ext_config($extConfig);
		if($mode === "display"){
			$where = $this -> _display_where($where);
		}else{
			$where = $this -> _search_where($where,$display_fields);
		}
		
		$this -> model -> table($this -> tbname);
		$db = $this -> model -> display($final_fields,$extConfig["displayNum"],$extConfig["countOf"],$extConfig["orderby"],$where);
		$pagehtml = $db -> getPageHTML();
		$rsbody = $db -> select() -> all();
		
		$view_descriptons = array();
		foreach($displaymask as $v){
			if(in_array($v['Field'],$final_fields)){
				$view_descriptons[$v['Field']] = $v;
			}
		}
		
		$ui -> setRsBody($rsbody);
		$ui -> setAllFieldsConfig($all_fields_display_config);
		$ui -> setManifestMask($manifest_fields);
		$ui -> setExtraMask($extra_fields);
		$ui -> setDisplayMask($display_fields);
		$ui -> setViewMask($view_descriptons);
		$ui -> setPageHTML($pagehtml);
		$ui -> setLastQueryString(S($this -> _last_search_query_string));
		return $this -> view -> display($ui);
	}	
	private function _display_where($where){
		if(!$where){
			return "1=1";
		}
		return $where;	
	}
	private function _search_where($where,$fields){
		if(!$where){
			if(S($this -> _last_search_query_string)){
				$where = S($this -> _last_search_query_string);	
			}else{
				$where = "";	
			}
		}else{
			S($this -> _last_search_query_string,$where);
		}
		$pk = $this -> base -> getPrimaryKey();
		$where = $this -> _search_type($where);
		$field_search = array();
		if($where["type"] === "pk"){
			return "`".$pk."` = ".$where['query'];	
		}else if ($where["type"] === "sql") {
			return $where["query"];
		}
		foreach($fields as $field){
			$type = $this -> base -> getFieldType($field);
			switch($type){
				case "text":
				case "tinyblob":
				case "tinytext":
				case "blob":
				case "mediumblob":
				case "mediumtext":
				case "longblob":
				case "longtext":	
					break;
				case "datetime":
				case "timestamp":
				case "date":
				case "time":
				case "year":
					if($where["type"] === "time"){
						$field_search[] = $field;	
					}
					break;
				case "enum":
				case "set":
					if($where["type"] === "string" || $where["type"] === "enum" ){
						$field_search[] = $field;	
					}
					break;
					
				case "binary":
				case "varbinary":
					if($where["type"] === "img"){
						$field_search[] = $field;	
					}
					break;
				case "varchar":
				case "char":
					if($where["type"] === "string"){
						$field_search[] = $field;	
					}
					break;
				case "tinyint":
				case "smallint":
				case "int":
				case "bigint":
				case "float":
				case "double":
				case "decimal":
				case "mediumint":
					if($where["type"] === "string" || $where["type"] === "number"){
						$field_search[] = $field;	
					}
					break;
				default:
					break;
			}

		}
		if(!$field_search){
			return '';		
		}
		$fields_where = array();
		switch($where["type"])	{
			case "time":
				$is_time = $this -> _parse_search_time_string($where["query"]);
				if(is_array($is_time)){
					
					foreach($field_search as $val){
						if(count($is_time) === 1){
							$fields_where[] = "`".$val."` > '".$where["query"]."' and `".$val."` < '".$is_time[0]."'";
						}else{
							$fields_where[] = "`".$val."` > '".$is_time[0]."' and `".$val."` < '".$is_time[1]."'";
						}							
					}
				}
				break;
			case "string":
			case "img":
				foreach($field_search as $val){
					$fields_where[] = "`".$val."` like '%".$where["query"]."%'";
				}
				break;
			case "number":
			case "enum":
				foreach($field_search as $val){
					$fields_where[] = "`".$val."` = '".$where["query"]."'";
				}
				break;
		}
		if($fields_where){
			return join(' or ',$fields_where);	
		}
		return '';		
	}
	private function _search_type($str) {
		if(preg_match("/^(string|time|number|sql|pk|enum|img):(.+)$/",$str,$matches)){
			return array(
				"type" => $matches[1],
				"query" => $matches[2]
			);
		}else{
			return array(
				"type" => "string",
				"query" => $str
			);
		}	
	}
	private function getPriv($config){
		/*	array(	
		add:int array string
		append
		edit
		update
		remove
		display
		view
		)
		*/
		$ret = array();
		if(!is_array($config))return array();
		if(array_key_exists("add",$config) && array_key_exists("append",$config) && $config["add"] && $config["append"]){
			$ret[] = "add";	
		}
		if(array_key_exists("edit",$config) && array_key_exists("update",$config) && $config["edit"] && $config["update"]){
			$ret[] = "edit";	
		}
		if(array_key_exists("remove",$config) && $config["remove"]){
			$ret[] = "remove";	
		}
		if(array_key_exists("view",$config) && $config["view"]){
			$ret[] = "view";	
		}
		return $ret;
	}
	private function getDisplayTitle(){
		if(!$this -> _init_check())return false;	
		$caption = $this -> base -> getDisplayTitle();
		return $this -> L("display form title",array("caption" => $caption));
	}
	private function getAddFormTitle(){
		if(!$this -> _init_check())return false;	

		$caption = $this -> base -> getAddFormTitle();
		return $this -> L("add form title",array("caption" => $caption));
	}
	private function getEditFormTitle(){
		if(!$this -> _init_check())return false;	

		$caption = $this -> base -> getEditFormTitle();
		return $this -> L("edit form title",array("caption" => $caption));
	}
//-----------------------------------------------------------------------------------
	private function geturl($p=''){
		$x = array(
			"add" => "add".$this -> tbname
			,"append" => "append".$this -> tbname
			,"edit" => "edit".$this -> tbname
			,"update" => "update".$this -> tbname
			,"index" => "index".$this -> tbname
			,"view" => "view".$this -> tbname
			,"remove" => "remove".$this -> tbname
			,"search" => "search".$this -> tbname
			
		);	
		return $p && array_key_exists($p,$x) ? $x[$p] : $x;
	}
	private function getFieldsDisplayName(){
		return $this -> base -> getFieldsDisplayName();
	}
	private function getFields(){
		return $this -> base -> getFields();
	}
//-----------------------------------------------------------------------------------	
	private function filterURL($url) {
		if(preg_match("/^[a-zA-Z\D_-]+$/",$url)){
			return R('HTTP') -> setMessage('action',$url);
		}
		return $url;
	}
	private function _init_check(){
		if(!$this -> tbname){
			$this -> view -> fail("",$this -> L("init function has not executed"),10);
			return false;	
		}
		return true;
	}	
	private function calc_union($mask_arr){
/*    [mask] => Array
        (
            [0] => Array
                (
                    [0] => id
                )

            [1] => Array
                (
                    [0] => nocomment
                    [1] => id
                )

            [2] => Array
                (
                    [0] => 
                )

        )	*/	
        	$t = array();
		foreach($mask_arr as $m){
			foreach($m as $v){
				if($v && !in_array($v,$t))$t[] = $v;	
			}
		}
		return $t;
		//return array_unique(array_merge($displaymask,$datawrapmask,$extramask));	
	}
	private function _getAllFieldsFromFieldManifest($manifest_fields){

		$uf = new tbUIUserFunc();
		$fn = array();
		$tx = array();
		$mk = array();
		foreach($manifest_fields as $key => $val){
			if(is_string($val)){
				$uf -> analyse($val);
				if(!$uf -> isValid()){
					$this -> error = $this -> L("load config manifest fields failed",array("field" => $val));
					return false;
				}				
			}else if(is_array($val)){
				$fn[] = $val;	
				$tx[] = '';	
				$mk[] = array($key);	
			}else if($val === ""){
				$fn[] = $val;	
				$tx[] = '';	
				$mk[] = array($key);	
				return true;	
			}else{
				$this -> error = "manifest is illegal:[".P($val)."]";
				return false;	
			}

		}
		if($uf -> isValid()){
			$this -> ___temp_result_manifest = array(
				"function" => $uf -> getFunctionName()
				,"text" => $uf -> getText()
				,"mask" => $uf -> getMask()
			);				
		}else{
			$this -> ___temp_result_manifest = array(
				"function" => $fn
				,"text" => $tx
				,"mask" => $mk
			);				
		}

		return true;
	}
	private function _getAllFieldsFromExtraFields($extra_fields){
		$uf = new tbUIUserFunc();

		$uf -> analyse($extra_fields);
		if(!$uf -> isValid()){
			$this -> error = $this -> L("load config manifest fields failed",array("field" => $extra_fields));
			return false;
		}

		$this -> ___temp_result_manifest = array(
			"function" => $uf -> getFunctionName()
			,"text" => $uf -> getText()
			,"mask" => $uf -> getMask()
		);	
		return true;
	}
	private function _getAllFieldsFromDisplayMask($displaymask){
		#echo P($displaymask);
		#echo P($this -> mask -> getMaskedDescripton($this -> tbname,$displaymask));
		return $this -> mask -> getFields(
			$this -> mask -> getMaskedDescripton($this -> tbname,$displaymask)
		);
	}
	private function _filter_field(& $fields){
		$reference = 	$this -> base  -> getFields();
		foreach($fields as $k => $v){
			if(!in_array($v,$reference)){
				unset($fields[$k]);	
			}
		}
		return $fields;
	}
	private function _parse_search_time_string($str){
		$v = new CDateValidator();
		if(count($time = explode(" ",trim($str))) === 2){
			if($v -> isValid($time[0]) && $v -> isValid($time[1])){
				return $time;
			}else{
				return false;	
			}
		}else if($v -> isValid($str)){
			$time = explode('-',$str);
			if(count($time) === 1){
				return array(date("Y-m-d",strtotime($str."+1year")));	
			}else if(count($time) === 2){
				return array(date("Y-m-d",strtotime($str."+1month")));
			}else if(count($time) === 3){
				return array(date("Y-m-d",strtotime($str."+1day")));
			}else{
				return false;	
			}
		}else{
			return false;	
		}
	}
}