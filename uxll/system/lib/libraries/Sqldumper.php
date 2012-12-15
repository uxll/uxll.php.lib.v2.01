<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Sqldumper.php
 */
class Csqldumper extends CModel{

	public function dump(){
		$ret = array();
		$data = $this -> query("SHOW TABLES") -> all();
		for($i=0;$i<count($data);$i++){
			foreach($data[$i] as $v){
				$ret[] = $this -> data2sql($v);
			}
		}
		return $ret;
	}

	public function table2sql($tab){
		$this -> table($tab);
		$tabledump = "DROP TABLE IF EXISTS $tab;\n";
		$createtable = $this -> query("SHOW CREATE TABLE $tab") -> row();
		$create = $createtable['Create Table'];
		$tabledump .= $create.";\n\n";
		return $tabledump;
	}

	public function data2sql($tab){
		$this -> table($tab);
		$tabledump = array();
		$tabledump[] = base64_encode("DROP TABLE IF EXISTS $tab;");
		$createtable = $this -> query("SHOW CREATE TABLE $tab") -> row();
		$tabledump[] = base64_encode($createtable['Create Table']);

		$rows = $this -> table($tab) -> select() -> all();
		$comma = "";
		foreach ($rows as $row){
			$comma .= "INSERT INTO $tab ";
			$item = array();
			$keys = array();
			foreach($row as $fk => $fv){
				$keys[] = "`".$fk."`";
				$item [] = "'".mysql_escape_string($fv)."'";
			}
			$comma .= "(".join(",",$keys).")VALUES(".join(",",$item).");\n";
			$tabledump[] = base64_encode($comma);		 
		}
		return $tabledump;
	}
	
}