<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Model.php
 */
class CModel{
	//'field','table','where','order','limit','page','alias','having','group','lock','distinct'
	private $engine = null;
	private $isValid = false;
	private $http = null;
	private $tabname;
	private $pk=null;
	private $pagehtml = '';
	private $distinct = '';
	private $fields = '*';
	private $join = '';
	private $where = '';
	private $group = '';
	private $having = '';
	private $order = '';
	private $limit = '';
	private $total = 0;
	//private $selectSql = 'SELECT%DISTINCT%%FIELDS%FROM%TABLE%%JOIN%%WHERE%%GROUP%%HAVING%%ORDER%%LIMIT%';
	public function __construct(){
		$this -> engine = CMysql::getInstance();
		$this -> engine -> connect();
		$this -> http = R("HTTP");
	}
	public function __call($name,$arguments){
		/*$ret = call_user_func_array(array($this -> engine,$name),$arguments);
		return get_class($ret) === get_class($this -> engine) ? $this : $ret;*/
		return false;
	}
	public function table($tbname){
		$this -> engine -> connect();
		$this -> tabname = $tbname;
		$this -> pk = $this -> getpk();
		return $this;
	}
	public function getpk(){
		if($this -> pk === null){
			$this -> pk = $this -> engine -> get_primary_key($this -> tabname);
			if(is_null($this -> pk))$this -> pk	= "";
		}
		return $this -> pk;
	}
	public function distinct(){
		$this -> distinct = 'DISTINCT';
		return $this;
	}
	public function fields($f){
		if(!empty($f)){
			if(is_array($f)){
				$this -> fields = '`'.join('`,`',$f).'`';
			}elseif(is_string($f)){
				$this -> fields = $f;
			}
		};
		return $this;
	}
	public function join($j){
		$this -> join = empty($j) ? '' : $j;
		return $this;
	}
	public function where($w){
		if(is_int($w)){
			$this -> where = '`'.$this -> getpk().'`' . '=' . $w;
		}elseif(is_array($w)){
			$where = array();
			foreach($w as $k => $v){
				$where[] = '`'.$key.'`=\''.$v.'\'';
			}
			$this -> where = join($where);
		}elseif(is_string($w)){
			$this -> where = $this -> handlewhere($w);
		}
		return $this;
	}
	public function group($a){
		$this -> group = empty($a) ? '' : $a;
		return $this;
	}
	public function having($a){
		$this -> having = empty($a) ? '' : $a;
		return $this;
	}
	public function order($a){
		$this -> order = empty($a) ? '' : $a;
		return $this;
	}
	public function limit($a){
		$this -> limit = empty($a) ? '' : $a;
		return $this;
	}
	public function select(){
		$sql = 'select';
		if(!empty($this -> distinct)){
			$sql .= ' '.$this -> distinct;
		}
		if(!empty($this -> fields)){
			$sql .= ' '.$this -> fields;
		}
		$sql.=' from `'.$this -> tabname.'`';
		if(!empty($this -> join)){
			$sql .= ' '.$this -> join;
		}
		if(!empty($this -> where)){
			$sql .= ' WHERE '.$this -> where;
		}
		if(!empty($this -> group)){
			$sql .= ' GROUP BY '.$this -> group;
		}
		if(!empty($this -> having)){
			$sql .= ' HAVING '.$this -> having;
		}
		if(!empty($this -> order)){
			$sql .= ' ORDER BY '.$this -> order;
		}
		if(!empty($this -> limit)){
			$sql .= ' LIMIT '.$this -> limit;
		}
//		echo $sql;
		$this -> engine -> query($sql);
		$this -> reset();
		return $this;
	}
	public function page($displayNum=5,$countOf=10,$currentPage=1,$pagegetargu='page'){
		/*---------------------------------
		$displayNum 分页页面上要显示的数目(页数偏移)
		$countOf 每页上的留言条数
		$currentPage 当前在第几页
		从第一页开始计数
		分页函数:Author:uxll@qq.com
		Created date:17:39 2007-3-24
		revised data 2012-1-5 11:03:44
		----------------------------------*/
		$smarty = new CTemplate();
		$txt="";
		$sql = "select count(".$this -> getpk().") as count from `".$this -> tabname."` where ".(empty($this -> where) ? 1 : $this -> where);
		$total = $this -> engine -> one($sql);
		$this -> total = $total;
		if(!is_array($total)){
			$this -> pagehtml = "";
			return $this;
		}else{
			$total = $total['count'];
		}

//		if(!$total)$this -> pagehtml = "";
//echo 'test';
		$displayNum = (int)$displayNum;
		$countOf = (int)$countOf;
		$currentPage = $currentPage===1 ? (int)$this -> http -> getMessage('arguments',$pagegetargu) : (int)$currentPage;

		if($currentPage<1)$currentPage = 1;
		if($currentPage>ceil($total/$countOf))$currentPage = ceil($total/$countOf);
		if($currentPage<=ceil($total/$countOf)){
			$StartPage = floor(($currentPage-1)/$displayNum)*$displayNum;
			if($StartPage<0){
				$this -> pagehtml = '';
				return $this;	
			}
			$smarty -> assign('total',array(
				'start' => $currentPage,
				'of' => L('page/of'),
				'total' => ceil($total/$countOf)
			));
			$smarty -> assign('previous',$StartPage>1 ? array(
				'url' => $this -> qs($StartPage,$pagegetargu),
				'text' => L('page/previous')
			): array(
				'url' => '',
				'text' => ''
			));
			$pages = array();
			for($i=1;$i<$displayNum+1;++$i){
				$pages[$StartPage+$i] = ($i+$StartPage)==$currentPage ? '' : $this -> qs($StartPage+$i,$pagegetargu);
				if(($StartPage+$i)*$countOf>=$total)break;
			}
			$smarty -> assign('pages',$pages);
			$smarty -> assign('next',ceil($total/$countOf)>($StartPage+$displayNum) ? array(
				'url' => $this -> qs($StartPage+$displayNum+1,$pagegetargu),
				'text' => L('page/next')
			): array(
				'url' => '',
				'text' => ''
			));
			$smarty -> assign('pageseparator','__UXLL_PAGE_SEPARATOR__');
			$smarty -> assign('urlpage',$this -> qs('__UXLL_PAGE_SEPARATOR__',$pagegetargu));
			$this -> pagehtml = $smarty -> fetch(LIBROOT."ui/tpl/page.tpl");
		}else{
			if($total <= 0){
				$this -> pagehtml = 'no date';
				return $this;
			}
			$this -> page($total,$displayNum,$countOf,ceil($total/$countOf),$pagegetargu);
		}
		$this -> limit((($currentPage-1)*$countOf).",".$countOf);
		return $this;
	}
	public function getPageHTML(){
		
		return $this -> pagehtml;
	}
	public function append($row){
		$this -> engine -> append($this -> tabname,$row);
		return $this;
	}
	public function update($row,$where=1){
		$this -> engine -> update($this -> tabname,$row,$this -> handlewhere($where));
		return $this;
	}
	public function remove($where){
		$this -> engine -> remove($this -> tabname,$this -> handlewhere($where));
		return $this;
	}
	public function one($pkid){
		if(preg_match("/^\d+$/",$pkid)){
			return $this -> engine -> one("select * from `".$this -> tabname."` where `".$this -> pk."` = ".$pkid);
		}
		return null;
	}
	public function rs(){
		return $this -> engine -> result();
	}
	public function total(){
		//it will be set after page fun executed
		return $this -> total;	
	}
	public function lastSql(){
		return $this -> engine -> getLastSql();	
	}
	public function getLastError(){
		return $this -> engine -> getLastError();	
	}
	public function all(){
		return $this -> engine -> all();	
	}
	public function getTableDecription($table){
		return $this -> engine -> getTableDecription($table);
	}
	public function getTabelComent($tbname){
		return $this -> engine -> getTabelComent($tbname);	
	}
	public function q($sql){
		return $this -> engine -> q($sql);
	}
	public function query($sql){
		return $this -> engine -> query($sql);
	}
	public function isValid() {
		return $this -> engine -> isValid();
	}
	public function getPrimaryKey($tbname) {
		return $this -> engine -> getPrimaryKey($tbname);
	}
//-------------------------------------------------------------------------------


	private function qs($p,$pagegetargu="page"){
//		var_dump($p);
		return $this -> http -> setMessage('arguments',array($pagegetargu=>$p));
	}
	private function reset(){
		$this -> distinct = '';
		$this -> fields = '*';
		$this -> join = '';
		$this -> where = '';
		$this -> group = '';
		$this -> having = '';
		$this -> order = '';
		$this -> limit = '';
		$this -> pagehtml = '';
	}
	private function handlewhere($where){
		//var_dump($where);
		if(is_array($where)){
			$where = $this -> getpk() . ' in ('.join(',',$where).')';
		}else{
			if(preg_match("/^\d+$/",$where)){
				$where = $this -> getpk() . ' = ' . $where;
			}elseif(preg_match("/^(([<>])|(!=))\d+$/",$where)){
				
				$where = $this -> getpk() . $where;
			}
		}
		return $where;
	}
}
