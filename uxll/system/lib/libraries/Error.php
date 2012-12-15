<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: Error.php
 */
class CError extends Exception{
	private $debug;//debug
	public function __construct($msg){
		$this -> debug = $GLOBALS['UXLL_CONFIG']['debug']['mode'];
		if($this -> debug){
			$this -> showErr($msg);	
		}else{
			$this -> log($msg);	
		}
	}
	public function halt(){
		exit;	
	}
	private function getsource($file, $line){
		if (!(file_exists($file) && is_file($file))) {return '';}
		$data = file($file);$count = count($data) - 1;
		$start = $line - 5;if ($start < 1) {$start = 1;}
		$end = $line + 5;if ($end > $count) {$end = $count + 1;}
		$returns = array();
		for ($i = $start; $i <= $end; $i++) {
			if( $i == $line ){
				$returns[] = "<div id='current'>".$i.".&nbsp;".$this -> highlight_code($data[$i - 1], TRUE)."</div>";
			}else{
				$returns[] = $i.".&nbsp;".$this -> highlight_code($data[$i - 1], TRUE);
			}
		}
		return $returns;
	}
	private function highlight_code($code){
	    if (ereg("<\?(php)?[^[:graph:]]", $code)) {
	        $code = highlight_string($code, TRUE);
	    } else {
	        $code = ereg_replace("(&lt;\?php&nbsp;)+", "",highlight_string("<?php ".$code, TRUE));
	    }
	    return $code;
	}	
	private function showErr($msg){
		ob_clean();
		$tpl = new CTemplate();
		$tpl -> assign('File',$this -> file);
		$tpl -> assign('Line',$this -> line);
		$tpl -> assign('Message',$msg);
		$tpl -> assign('Trace',json_encode($this -> getTrace()));
		$tpl -> assign('source',$this -> getsource($this -> file,$this -> line));
		$tpl -> display(LIBROOT.'ui/tpl/Error.tpl');
		$this -> halt();
	}
	private function log($msg){
		$str = "File:".$this -> file."\r\n";
		$str.= "Line:".$this -> line."\r\n";
		$str.= "Message:".$msg."\r\n----------------------------------------------------------------------------\r\n\r\n";
		file_put_contents(ROOT.'dc/log/'.date('Ymd').'.txt',$str,FILE_APPEND);
	}
	
}