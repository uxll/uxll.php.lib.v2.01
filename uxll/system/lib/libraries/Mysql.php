<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Mysql.php
 */
class CMysql{
	private static $_instance = null;
	private $link = null;
	private $result;
	private $count = 0;
	private $config = null;
	private $sql;
	private $total;
	private $tasks = array();	
	private $lastError = '';
	private $debug = false;
	private $table_fields_hash = array();
	private $table_pk_hash = array();
	private $table_description_hash = array();
	private $table_comment_hash = array();
	private $error = false;
	public function getLastError(){
		return $this -> lastError;
	}	
	public function isValid(){
		return !$this -> error;
	}		
	private function __construct($debug = false){
		$this -> debug = $debug;
	}
	public function __set($name,$value){
	}
	public function __get($name){
	}
	public static function getInstance($flag = false){
		if (null === self::$_instance){
			self::$_instance = new self($flag);
		}
		return self::$_instance;
	}
	public function connect($config=null){
		if($this -> link)return $this;
		$this -> config = is_null($config) ? require_once(ROOT.'privateData/dbConnect.php') : $config;
		$this -> link = @mysql_connect($this -> config["hostname"], $this -> config["username"], $this -> config["password"]);
		if (! $this -> link){
			$this -> error = "Can not connect MySQL Server";
			return false;
		}
		if (!mysql_select_db('INFORMATION_SCHEMA', $this -> link)){
			$this -> error = "Can not connect MySQL Database";
			return false;
		}
		if ($this -> config["charset"]) {
			mysql_unbuffered_query("SET NAMES '".$this -> config["charset"]."'");
			//return false;
		}				
		$this -> query("SELECT TABLE_NAME,TABLE_COMMENT FROM `INFORMATION_SCHEMA`.`TABLES`  WHERE `table_schema` = '".$this -> config["database"]."'");
		foreach($this -> all() as $val){
			$this -> table_comment_hash[$val['TABLE_NAME']] = $val['TABLE_COMMENT']; 
		}
		
		if (!mysql_select_db($this -> config["database"], $this -> link)){
			$this -> error = "Can not connect MySQL Database";
			return false;
		}

		return $this;
	}
	public function query($sql){
		if(!$this -> link){
			$this -> lastError ='no connecting';
			return false;	
		}
		$this -> count ++;
		$this -> sql = $sql;
		$this -> tasks[] = $sql;
		$this -> result = mysql_query($sql, $this -> link);
		if (!$this -> result) {
			$this -> lastError = mysql_error();
			$this -> total = 0;
			$this -> error = true;
		}else{
			$this -> total = mysql_num_rows($this -> result);
			$this -> lastError = '';
			$this -> error = false;
		}
		
		return $this;
	}
	public function q($sql){
		$this -> tasks[] = $sql;
		$this -> count ++;
		if(mysql_unbuffered_query($sql)){
			$this -> lastError = '';
			$this -> error = false;
		}else{
			$this -> lastError = mysql_error();
			$this -> error = true;
		};
		
		$this -> total = 0;
		return $this;
	}
	public function row(){
		if($this -> result)return mysql_fetch_array($this -> result, MYSQL_ASSOC);
		return null;
	}
	public function affected_rows() {
		return mysql_affected_rows($this -> link);
	}
	public function num() {
		return $this -> total;
	}
	public function insert_id(){
		return mysql_insert_id($this -> link);
	}
	public function query_count() {
		return $this -> count;
	}
	public function get_table_names(){
		$result = mysql_list_tables($this -> config["database"]);
		$num_tables = mysql_num_rows($result);
		$tables = array();
		for ($i = 0; $i < $num_tables; $i++) {
			$tables[] = mysql_tablename($result, $i);
		}
		mysql_free_result($result);
		return $tables;
	}
	public function get_primary_key($table){
		if(!isset($this -> table_pk_hash[$table])){
			$k = $this -> getTableDecription($table);
		}else{
			return $this -> table_pk_hash[$table];	
		}	
		foreach($k as $i => $v){
			if($v['Key'] === 'PRI'){
				$this -> table_pk_hash[$table] = $v['Field'];
				return $v['Field'];
			}
		}	
		return null;
	}
	public function getPrimaryKey($table){
		return $this -> get_primary_key($table);
	}
	public function get_result_fields($table){
		if(!isset($this -> table_fields_hash[$table])){
			$this -> query("SHOW COLUMNS FROM `$table`");
			$k = $this -> all();
		}else{
			$k = $this -> table_fields_hash[$table];
			return $k;
		}

		foreach($k as $i => $v){
			if(preg_match("/^([a-z]+)\((\d+)\) [a-z]+$/",$v['Type'],$matches)){
				$k[$i]['Type'] = array(
					'type' => strtolower($matches[1]),
					'len'  => $matches[2]
				);
			}else if(preg_match("/^([a-z]+)\((\d+)\)$/",$v['Type'],$matches)){
				$k[$i]['Type'] = array(
					'type' => strtolower($matches[1]),
					'len'  => $matches[2]
				);
			}else if(preg_match("/^([a-z]+) [a-z]+$/",$v['Type'],$matches)){
				$k[$i]['Type'] = array(
					'type' => strtolower($matches[1]),
					'len'  => null
				);
			}else if(preg_match("/^([a-z]+)$/",$v['Type'],$matches)){
				$k[$i]['Type'] = array(
					'type' => strtolower($matches[1]),
					'len'  => null
				);
			}else if(preg_match("/^([a-z]+)\((('[\w]+',)*'[\w]+')\)$/",$v['Type'],$matches)){
				$data = 'return array('.$matches[2].');';
//				die($data);
				$data = eval($data);
		//		array_map('t',$data);
				$k[$i]['Type'] = array(
					'type' => strtolower($matches[1]),
					'data' => $data,
					'len'  => null
				);
			}else{
				die('mysql::get_result_fields:'.$v['Type'])	;
			}
		}
		$this -> table_fields_hash[$table] = $k;
		return $k;
	}
	public function getTableFields($table){
		return $this -> get_result_fields($table);
	}
	public function getTableDecription($table){
		if(!isset($this -> table_description_hash[$table])){
			$this -> query("SHOW FULL COLUMNS FROM `$table`");
			$k = $this -> all();
			$this -> table_description_hash[$table] = $k;
		}else{
			$k = $this -> table_description_hash[$table];
		}
		return $k;	
	}
	public function getLastSql(){
		return $this -> sql;	
	}
	public function sql_select($tbname,$where="1",$limit=0,$fields="*",$orderby="id",$sort="DESC"){
		$sql = "SELECT ".$fields." FROM `".$tbname."` ".($where?" WHERE ".$where:"")." ORDER BY ".$orderby." ".$sort.($limit ? " limit ".$limit:"");
		return $sql;
	}
	public function sql_insert($tbname,$row){
		$sqlfield = '';
		$sqlvalue = '';
		foreach ($row as $key=>$value) {
			$sqlfield .= '`'.$key."`,";
			$sqlvalue .= "'".$value."',";
		}
		return "INSERT INTO `".$tbname."`(".substr($sqlfield, 0, -1).") VALUES (".substr($sqlvalue, 0, -1).")";
	}
	public function sql_update($tbname,$row,$where){
		$sqlud = '';
		foreach ($row as $key => $value) {
			$sqlud .= '`'.$key."`= '".$value."',";
		}
		return "UPDATE `".$tbname."` SET ".substr($sqlud, 0, -1)." WHERE ".$where;
	}
	public function sql_delete($tbname,$where){
		return "DELETE FROM `".$tbname."` WHERE ".$where;
	}
	public function append($tbname,$row){
		$sql = $this -> sql_insert($tbname, $row);
		return $this -> q($sql);
	}
	public function update($tbname,$row,$where=1){
		$sql = $this -> sql_update($tbname, $row, $where);
		return $this -> q($sql);
	}
	public function remove($tbname,$where){
		$sql = $this -> sql_delete($tbname,$where);
		return $this -> q($sql);
	}
	public function all(){
		$row = array();
		while($row[] = $this -> row());
		array_pop($row);
		$this -> free();
		return $row;
	}
	public function result(){
		return $this -> result;
	}	
	public function get_last_error(){
		return $this -> lastError;	
	}
	public function one($sql){
		$this -> query($sql);
		$row = $this -> row();
		$this -> free();
		return $row;
	}
	public function halt($e=""){
		new CError('SQL:'.$this-> sql.'<br>'.$e);
	}	
	public function free(){
		if($this -> result)mysql_free_result($this -> result);
		return $this;
	}
	public function close(){
		if ($this -> link) mysql_close($this -> link);
		return $this;
	}	
	public function __destruct(){
		$this -> close();
	}
	public function test(){
		new CDebug($this -> all());	
	}
	public function debug(){
		if(!$this -> debug)return false;
		return $this -> tasks;
	}
	public function getTabelComent($tbname){
		//var_dump($this -> table_comment_hash);
		if(array_key_exists($tbname,$this -> table_comment_hash)){
			return $this -> table_comment_hash[$tbname];
		}
		return false;
	}
}