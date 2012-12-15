<?php
class tbManifest{
	
	//��3�ָ�ʽ
	//1.""
	//2.array()
	//3.�ַ���CLASS::METHOD|FIELD0,FIELD1,...	
	
	//��UI�е���EXECUTE�����ݸ�ʽ��ROW
	
	public function execute($tbmanifest,$row,$field){
		if($tbmanifest === "")return $row[$field];
		if(is_array($tbmanifest)){
			if(array_key_exists($row[$field],$tbmanifest))return $tbmanifest[$row[$field]];
			return "<span class='manifest-wrong-index'><span class='ui-icon ui-icon-help ui-button'>"."</span>".$row[$field]."</span>";
		} 
		if(is_string($tbmanifest)){
			$fn_args = explode("|",$tbmanifest);
			$cls = explode("::",$fn_args[0]);
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
			$controller = $rc -> newInstance();
			$method = $rc -> getMethod('execute');			
			return $method -> invokeArgs($controller, array($row,$cls[1],$field));		
		}
		return "wrong manifest config:".P($tbmanifest);
	}
}