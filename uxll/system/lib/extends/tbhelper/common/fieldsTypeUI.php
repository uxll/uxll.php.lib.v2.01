<?php
class fieldsTypeUI{
	/*
		输入action,type,extraData=array()
		isValid()
		getLastError()
		getHtml()
	*/
	private $clsname_datetime = "datetimepicker";
	private $clsname_date = "datepicker";
	private $clsname_enumview = "showtitle";
	private $clsname_viewiframe = "viewiframe";
	private $clsname_tbhelper_icon = "tbhelper_icon";
	
	public function getHTML($action,$type,$extraData = array()){
		$method = "_".$this -> getType($type)."_".$action;
		return $this -> $method($extraData);
	}	

	public function getType($type) {
		switch($type){
			
			case "text":
			case "tinyblob":
			case "tinytext":
			case "blob":
			case "mediumblob":
			case "mediumtext":
			case "longblob":
			case "longtext":	
				return "textarea";
			case "datetime":
			case "timestamp":
				return "datetime";
			case "date":
			case "time":
			case "year":
				return "date";
			case "enum":
				return "enum";
			case "set":
				return "set";
				
			case "binary":
			case "varbinary":
				return "varbinary";
			case "varchar":
			case "char":
			case "tinyint":
			case "smallint":
			case "int":
			case "bigint":
			case "float":
			case "double":
			case "decimal":
			case "mediumint":
			default:
				return "text";
		}
	}
//------------------- :: textarea :: ---------------------------------------------------------
	private function _textarea_add($extraData){//*cols *rows name defaultvalue
		return "<textarea cols='".(isset($extraData['cols']) ? $extraData['cols'] : '')."' rows='".(isset($extraData['rows']) ? $extraData['rows'] : '')."' name='".$extraData['name']."'>".(isset($extraData['defaultvalue']) ? $extraData['defaultvalue'] : '')."</textarea>"
		;
	}	
	private function _textarea_edit($extraData){//^
		return $this -> _textarea_add($extraData);
	}	
	private function _textarea_view($extraData){//defaultvalue
		//先检查有没有HTML，没有HTML用NL2BR，有用一个框架HTML显示原来的样子
		$str = $extraData['defaultvalue'];
		if($this -> _has_html($str)){
			return $this -> iframe_html($str);
		}else{
			return nl2br($str);
		}
	}	
	private function _textarea_display($extraData){//defaultvalue
		//先检查有没有HTML，没有HTML用NL2BR，有显示HTML原来的样子
		$str = $extraData['defaultvalue'];
		if($this -> _has_html($str)){
			return htmlspecialchars($str, ENT_NOQUOTES);
		}else{
			return nl2br($str);
		}
	}	
	
//------------------- :: datetime :: ---------------------------------------------------------
	private function _datetime_add($extraData){//name defaultvalue
		return 
		"<input name='".$extraData['name']."' value='".(
				isset($extraData['defaultvalue']) ? 
				$extraData['defaultvalue'] : 
				''
			)."' class='".$this -> clsname_datetime."'>"
		;
	}	
	private function _datetime_edit($extraData){//^
		return $this -> _datetime_add($extraData);
	}	
	private function _datetime_view($extraData){//defaultvalue
		$dt = $extraData['defaultvalue'];
		$dta = explode(" ",$dt);
		return "<span title='".($dt)."'>".$dta[0]."</span>";
	}	
	private function _datetime_display($extraData){//defaultvalue
		return $extraData['defaultvalue'];
	}		
	
//------------------- :: date :: ---------------------------------------------------------
	private function _date_add($extraData){//name defaultvalue
		return 
		"<input name='".$extraData['name']."' value='".(
				isset($extraData['defaultvalue']) ? 
				$extraData['defaultvalue'] : 
				''
			)."' class='".$this -> clsname_date."'>"
		;
	}	
	private function _date_edit($extraData){//^
		return $this -> _date_add($extraData);
	}	
	private function _date_view($extraData){//defaultvalue
		return $extraData['defaultvalue'];
	}	
	private function _date_display($extraData){//defaultvalue
		return $extraData['defaultvalue'];
	}		
	
//------------------- :: enum :: ---------------------------------------------------------
	private function _enum_add($extraData){//*value enum defaultvalue
		$values = isset($extraData['value']) ? $extraData['value'] : false;
		$enum = $extraData['enum'];
		if($values){
			$enum = array_combine($enum,$values);	
		}else{
			$enum = array_combine($enum,$enum);	
		}
		$defaultvalue = $extraData['defaultvalue'];
#echo $defaultvalue;
		$name = $extraData['name'];
		
		$fragment = '';
		$fragment.= "<select name='".$name."'>";
#die(P($enum));
		foreach($enum as $k => $v){
			$fragment.= "<option".
			($k===$defaultvalue ? " selected" : "")
			." value='".
			htmlentities($k,ENT_QUOTES)
			."'>".$v."</option>";
		}
		$fragment.= "</select>";
		return $fragment;
	}	
	private function _enum_edit($extraData){//^
		return $this -> _enum_add($extraData);
	}	
	private function _enum_view($extraData){//*value enum defaultvalue
		$values = isset($extraData['value']) ? $extraData['value'] : false;
		$enum = $extraData['enum'];
		if($values){
			$enum = array_combine($enum,$values);	
		}
		$defaultvalue = $extraData['defaultvalue'];
		return $values ? '<span title="'.$defaultvalue.'" class="'.$this -> clsname_enumview.'">'.$enum[$defaultvalue].'</span>' : $extraData['defaultvalue'];
	}	
	private function _enum_display($extraData){//^
		return $this -> _enum_view($extraData);
	}	

//------------------- :: set :: ---------------------------------------------------------
	private function _set_add($extraData){//*value enum defaultvalue
		$values = isset($extraData['value']) ? $extraData['value'] : false;
		$enum = $extraData['enum'];
		if($values){
			$enum = array_combine($enum,$values);	
		}else{
			$enum = array_combine($enum,$enum);	
		}
		$defaultvalue = $extraData['defaultvalue'];
		$defaultvalue = explode(",",$defaultvalue);
		$name = $extraData['name'];
		
		$fragment = '';
		$fragment.= "<select multiple='multiple' name='".$name."[]'>";
		foreach($enum as $k => $v){
			$fragment.= "<option".(in_array($k,$defaultvalue) ? " selected" : "")." value='".
			htmlentities($k,ENT_QUOTES)."'>".$v."</option>";
		}
		$fragment.= "</select>";
		return $fragment;
	}	
	private function _set_edit($extraData){//^
		return $this -> _set_add($extraData);
	}	
	private function _set_view($extraData){//*value enum defaultvalue

		$values = isset($extraData['value']) ? $extraData['value'] : false;
		$enum = $extraData['enum'];
		if($values){
			$enum = array_combine($enum,$values);	
		}else{
			$enum = array_combine($enum,$enum);	
		}
		
//echo(P($enum));		
		$defaultvalue = $extraData['defaultvalue'];
			
		
		$defaultvalue = explode(",",$defaultvalue);
#echo(P($defaultvalue));		
		$fragment = array();
		foreach($enum as $k => $v){
			if(in_array($k,$defaultvalue)){
				$fragment[] = "<span title='".$k."'>".$v."</span>";
			}
		}
		return join(' , ',$fragment);	
	}	
	private function _set_display($extraData){//^
		return $this -> _set_view($extraData);
	}	

//------------------- :: varbinary :: ---------------------------------------------------------
	private function _varbinary_add($extraData){//name
		return 
		"<input type='file' name='".$extraData['name']."' >"
		;
	}	
	private function _varbinary_edit($extraData){//name defaultvalue
		return "<input name='".$extraData['name']."' value='".$extraData['defaultvalue']."'>";
	}	
	private function _varbinary_view($extraData){//defaultvalue *width *height
		$w = isset($extraData['width']) ? $extraData['width'] : 75;
		$h = isset($extraData['height']) ? $extraData['height'] : 75;
		return $this -> _showPic($extraData['defaultvalue'],$w,$h);
	}	
	private function _varbinary_display($extraData){//defaultvalue
#		die("here");
		return $this -> _showPic($extraData['defaultvalue']);
	}

//------------------- :: text :: ---------------------------------------------------------
	private function _text_add($extraData){//len name defaultvalue
		return 
		"<input maxlength='".$extraData['len']."' type='text' name='".$extraData['name']."' value='".$extraData['defaultvalue']."'>"
		;
	}	
	private function _text_edit($extraData){//^
		return $this -> _text_add($extraData);
	}	
	private function _text_view($extraData){//defaultvalue
		return $extraData['defaultvalue'];
	}	
	private function _text_display($extraData){//defaultvalue
		return $extraData['defaultvalue'];
	}	

//--------------------------------------------
	private function _has_html($str){
		return preg_match("/<[a-zA-Z]+[^>]*>/",$str);
	}	
	private function iframe_html($str){
		//echo $str;
		return "
		".$str."
";	
	}
	private function _showPic($pic,$w=20,$h=20){
		return '<img src="'.$pic.'" width='.$w.' height='.$h.' class="'.$this -> clsname_tbhelper_icon.'">';
	}	
}