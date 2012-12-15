<?php
/**
 *  @version: 2.01
 *  @date: 2011-2-23 10:44:29 
 *  @author: uxll@qq.com
 *  @file: Email.php
 */
class Email{
	var $smtpsocket = false;
	var $debug = true;
	var $prefix = "";
	var $host = "";
	var $port = "";
	var $user = "";
	var $pass = "";
	var $type = "HTML";
	var $body = "";
	var $to = "";
	var $from = "";
	var $subject = "";

	var $CRLF = "\r\n";
	var $timer = 10;

	function smtp($h,$o,$u,$p,$t,$s,$b){
		$this->host = $h;
		$this->port = $o;
		$this->user = $u;
		$this->from = $u;
		$this->pass = $p;
		$this->subject = $s;
		$this->to = $t;
		$this->body = $b;
	}

	function connect(){
		$this -> smtpsocket = fsockopen($this -> prefix . $this -> host,$this -> port, $errno, $errstr, $this -> timer);
		if(!($this -> smtpsocket && $this->smtp_ok())){
			$this -> err("Connect failed");
			return false;
		}else{
			return true;
		}
		if(substr(PHP_OS, 0, 3) != "WIN")
		socket_set_timeout($this->smtpsocket, $timer, 0);
	}
	function smtp_ok(){
		while($response = fgets($this -> smtpsocket, 512)){
			if(substr($response,3,1) == " "){break;}
		}
		if (!ereg("^[23]", $response)){
			$this -> err($response);
			fputs($this -> smtpsocket, "QUIT" . $this -> CRLF);
			$response = fgets($this -> smtpsocket, 512);
			if ($response != 221){
				echo "Quit command executed failed...<br>";
			}
			return false;
		}
		return true; 
	}
	function err($str = ""){
		if($this -> debug){
    	echo "> Error: Remote host returned <font color=red>".$str."</font><br>" . $this -> CRLF;
		}		
	}
	function sendcmd($cmd,$arg = ""){ 
		if($arg){ 
			if(!$cmd) $cmd = $arg; 
			else $cmd = $cmd . " " . $arg; 
		}
		fputs($this -> smtpsocket, $cmd . $this -> CRLF); 
		while($response = fgets($this -> smtpsocket, 512)){
			if(substr($response,3,1) == " "){break;}
		}
		if (!ereg("^[23]", $response)){
			$this -> err($response);
			fputs($this -> smtpsocket, "QUIT" . $this -> CRLF);
			fgets($this -> smtpsocket, 512);
			return false;
		}else{
			//echo "> Command executed successful:<font color=green>[".$cmd."]</font><font color=blue>".$response."</font>\r\n<br>";
		}
		return true; 
	} 
	function send(){
		if(!$this -> smtpsocket){
			$this -> connect();
		}
		if (!$this -> sendcmd("EHLO", $this -> user)){ 
			return $this -> err ("sending HELO command"); 
		}
		if (!$this -> sendcmd("AUTH LOGIN")){ 
			return $this -> err("sending auth login command"); 
		} 
		if (!$this -> sendcmd(base64_encode($this -> user))){ 
			return $this -> err("sending user command"); 
		} 
		if (!$this -> sendcmd("", base64_encode($this -> pass))){ 
			return $this -> err("sending pass command"); 
		} 
		if (!$this -> sendcmd("MAIL", "FROM: <" . $this -> from . ">")){ 
			return $this-> err("sending MAIL FROM command"); 
		} 
		if (!$this -> sendcmd("RCPT", "TO:<".$this -> to.">")){ 
			return $this -> err("sending RCPT TO command"); 
		} 
		if (!$this -> sendcmd("DATA")){ 
			return $this -> err("sending DATA command"); 
		} 
		if (!$this -> message()){ 
			return $this -> err("sending message"); 
		} 
		if (!$this -> sendcmd("QUIT")){ 
			return $this -> err("sending QUIT command"); 
		} 
		fclose($this -> smtpsocket);
		return TRUE; 
	} 
	function message(){
		$header="";
		$header .= "Date: " . date("r") . $this -> CRLF . $this -> CRLF;
		$header  = "Return-Path: ". $this -> from . $this -> CRLF;
		$header .= "To: " . $this -> to . $this -> CRLF;
		$header .= "From: " . "<" . $this -> from . ">" . $this -> CRLF;
		$header .= "Reply-To: <" . $this -> from . ">" . $this -> CRLF;
		$header .= "Subject: " . $this -> subject . $this -> CRLF;
		$header .= "Message-ID: <" . md5(time()) . ">" . $this -> CRLF;
		$header .= "X-Priority: 3" . $this -> CRLF;
		$header .= "X-Mailer: Apocalypse (www.uxll.com) [version 1.0.0]" . $this -> CRLF;
		$header .= "MIME-Version: 1.0" . $this -> CRLF;
		$header .= "Content-Type: multipart/mixed;" . $this -> CRLF;
		$header .= "\tboundary=\"b1_" . md5("Apocalypse") . "\"". $this -> CRLF . $this -> CRLF . $this -> CRLF;


		$body  = "";
		$body .= "--b1_" . md5("Apocalypse") . $this -> CRLF;
		$body .= "Content-Type: text/html; charset = \"gb2312\"" . $this -> CRLF;
		$body .= "Content-Transfer-Encoding: 8bit" . $this -> CRLF . $this -> CRLF;
		$body .= $this -> body . $this ->  CRLF;

		fputs($this -> smtpsocket, $header . $body . $this -> CRLF . "." . $this -> CRLF);
		return $this->smtp_ok();  
	}
} 
/*
if(
isset($_POST['host'])
&&isset($_POST['port'])
&&isset($_POST['user'])
&&isset($_POST['pass'])
&&isset($_POST['to'])
&&isset($_POST['title'])
&&isset($_POST['content'])
){
	$buglolmailer = new smtp($_POST['host'],$_POST['port'],$_POST['user'],$_POST['pass'],$_POST['to'],$_POST['title'],stripcslashes($_POST['content']));
	if($buglolmailer -> send())echo "message has sent";
	else echo "message sent failed..";
}
*/