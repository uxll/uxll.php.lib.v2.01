<?php
class tbhelperModel extends CModel{
/*
PK不管怎么设置都不能添加编辑
*/	
	private $error = 0;
	private $last_search_txt = '';

	
	
	public function getLastError(){
		return $this -> error;
	}	
	
	public function display($display_fields,$pagecount,$pageoffset,$orderby,$where) {
		$db = $this -> fields($display_fields);
		if($where){
			$db -> where($where);
		}
		return $db -> order($orderby) -> page($pagecount,$pagecount);
	}
	
	public function append($msg,$append_fields,$ui){
		
		
		
		
		$datawrap = $ui -> getDatawrap();
		$patterns = $ui -> getPatterns();
		$display_fields = $ui -> getFieldsDisplayName();

	
		$row = array();
		$msg -> turnOn();
		foreach($append_fields as $field){
			if(array_key_exists($field,$patterns))	{
				$r = $this -> checkPattern($msg[$field],$patterns[$field]);
				if(!$r){
					$this ->  error = tbhelper::L("Pattern matched faile",array(
						"pattern" => htmlspecialchars($patterns[$field]),
						"field" => htmlspecialchars($display_fields[$field],ENT_NOQUOTES,'UTF-8'),
						
						"content" => htmlspecialchars($msg[$field])
					));	
					return false;
				}
			}
			if(array_key_exists($field,$datawrap))	{
				$r = $this -> datawrap($msg[$field],$datawrap[$field]);
				if($r['flag']){
					$row[$field] = $r['data'];
				}else{
					$this -> error = $r['data'];
					return false;		
				}
			}else{
				$row[$field] = $this -> defaultDataWrap($msg[$field]);
			}
		};
		parent::append($row);
		if(!parent::isValid()){
			$this -> error = parent::getLastError();
			return false;	
		}
		return true;
	}
	
	
	public function update($msg,$update_fields,$ui){
		$pks = $msg[$this -> getPrimaryKey($ui -> getTbname())];
		$datawrap = $ui -> getDatawrap();
		$patterns = $ui -> getPatterns();
		$display_fields = $ui -> getFieldsDisplayName();

		$msg -> turnOn();
		foreach($pks as $pk){
			$row = array();
			
			foreach(array_keys($update_fields) as $field){
				if(array_key_exists($field,$patterns))	{
					$r = $this -> checkPattern($msg[$field][$pk],$patterns[$field]);
					if(!$r){
						$this ->  error = tbhelper::L("Pattern matched faile",array(
							"pattern" => htmlspecialchars($patterns[$field]),
							"field" => htmlspecialchars($display_fields[$field],ENT_NOQUOTES,'UTF-8'),
							"content" => htmlspecialchars($msg[$field][$pk])
						));	
						return false;
					}
				}
				if(array_key_exists($field,$datawrap))	{
					$r = $this -> datawrap($msg[$field][$pk],$datawrap[$field]);
					if($r['flag']){
						$row[$field] = $r['data'];
					}else{
						$this -> error = $r['data'];
						return false;		
					}
				}else{
					if($update_fields[$field] === 'set'){
						if(isset($msg[$field]) && isset($msg[$field][$pk]) && is_array($msg[$field][$pk])){
							$row[$field] = join(",",$msg[$field][$pk]);
						}else{
							$row[$field] = '';
						}
						
					}else{
						$row[$field] = $msg[$field][$pk];
					}
					
				}
			};
			parent::update($row,"`".$this -> getPrimaryKey($ui -> getTbname())."` = ".$pk);
			if(!parent::isValid()){
				$this -> error = parent::getLastError();
				return false;	
			}			
		}
		return true;
	}
	public function verifyFeedback($where) {
		if($where){
			
		}else{
			$this -> error = "where is illegal:".$where;	
			return false;
		}
	}
	public function  remove($where){
		parent::remove($where);
		if(!parent::isValid()){
			$this -> error = parent::getLastError();
			return false;	
		}
		return true;
	}
	
	public function getEditRow($final_fields,$where) {
		return $this -> fields($final_fields) -> where($where) -> select() -> all();
	}
	
//-----------------------------------------------------------------------------------------------------------------------------
	
	private function checkPattern($f,$p) {
		return preg_match($p,$f);
	}
	private function datawrap($f,$dw) {
		
		$_dw = explode("::", $dw);
		if(count($_dw) === 2 && $_dw[0] && $_dw[1]){
			if(!class_exists($_dw[0])){
				return array(
					"flag" => false,
					"data" => tbhelper::L("class does not exist",array("class"=>$_dw[0]))
				);
			}
			$rc = new ReflectionClass($_dw[0]);
			$controller = $rc -> newInstance();
			if(!$rc -> hasMethod($_dw[1])){
				return array(
					"flag" => false,
					"data" => tbhelper::L("method does not exist",array("method"=>$_dw[1]))
				);	
			};	
			$method = $rc -> getMethod($_dw[1]);			
			$r = $method -> invokeArgs($controller, array($f));
			return array(
				"flag" => true,
				"data" => $r
			);
		}else{
			return array(
				"flag" => false,
				"data" => tbhelper::L("datawrap is illegal",array("dw"=>$dw))
			);	
		}
	}
	
	private function defaultDataWrap($data){
		if(is_array($data))	{
			return join(",",$data);
		}
		return $data;
	}
}