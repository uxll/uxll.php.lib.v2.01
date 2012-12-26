<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Validator.php
 */
interface IValidator{
	public function isValid($value);
}
class CRequiredValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/.+/', trim($value));
	}
}
class CEmptyValidator implements IValidator{
	public function isValid($value) {
		return (empty($value) || $value=="");
	}
}
class CEmailValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', trim($value));
	}
}
class CIpValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9])\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[1-9]|0)\.(25[0-5]|2[0-4][0-9]|[0-1]{1}[0-9]{2}|[1-9]{1}[0-9]{1}|[0-9])$/', trim($value));
	}
}
class CNumberValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^[1-9]\d*(\.\d+)?$/', trim($value)) || preg_match('/^0\.\d+$/', trim($value)) || preg_match('/^0$/', trim($value));
	}
}
class CIntegerValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^[1-9]\d*$/', trim($value)) || preg_match('/^0$/', trim($value));
	}
}
class CClassValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^C[A-Z]\w*$/', trim($value));
	}
}
class CInterfaceValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^I[A-Z]\w*$/', trim($value));
	}
}
class CFileValidator implements IValidator{//windows path
	public function isValid($value) {
		return preg_match('/^[^\/\:\*\?\"\>\<\|]+$/', trim($value));
	}
}
class CLabelValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^[a-z]+$/', ($value));
	}
}
class CKeyValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^[a-z\d]+$/', ($value));
	}
}
class CTreeKeyPathValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^\/([a-z0-9]+\/)*$/', ($value));
	}	
}
class C2dimensionLevelDataValidator implements IValidator{
	/**
		[
			[]//只能2－3个，每个都是数字，每行都相等,就2列是价格
		
		]
	*/
	public function isValid($value) {
		$len = null;
		if(is_array($value)){
			foreach($value as $k => $v){
				if(!is_array($v))return false;
				if(!is_null($len)){
					if($len != count($v))return false;
				}
				$len = count($v);
				if($len !=2 || $len != 3)return false;
				foreach($v as $kk => $vv){
					if(!is_double($vv))return false;	
				}
			}
			return true;
		}
		return false;
	}	
}
class CImgPathValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^[^\/\:\*\?\"\>\<\|]+\.(png|jpg|gif)$/',($value));
	}		
}
class CTpdcTreePathValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^#([\da-z]+:\/([\da-z]+\/)+)?$/',$value);
	}	
}
class CTimeValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^[\/\d\- \:]+$/',$value);
	}	
}
class CDateValidator implements IValidator{
	public function isValid($value) {
		return preg_match('/^2[0-9][0-9][0-9]((-[01][0-9])||(-[01][0-9]-[0-3][0-9]))?$/',$value);
	}	
}

