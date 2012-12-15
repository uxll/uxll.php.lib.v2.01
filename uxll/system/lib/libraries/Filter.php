<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Filter.php
 *  @usage:
 *  $input = new CInputElement(); 
 *  $input->addFilter(new CNullFilter())
 *  ->addFilter(new CTrimFilter())
 *  ->addFilter(new CHtmlEntitiesFilter());
 *  $input->setValue(' You should use the <h1>-<h6> tags for your headings.');
 *  echo $input->getValue(), "\n";
 */
interface IFilter{
	public function filter($value);
}
class CTrimFilter implements IFilter{
	public function filter($value){
		return trim($value);
	}
}
class CNullFilter implements IFilter{
	public function filter($value){
		return $value ? $value : '';
	}
}
class CHtmlEntitiesFilter implements IFilter{
	public function filter($value){
		return htmlentities($value);
	}
}
class CAddslashesFilter implements IFilter{
	public function filter($value){
		if (get_magic_quotes_gpc())return $value;
		return addslashes($value);	
	}	
}