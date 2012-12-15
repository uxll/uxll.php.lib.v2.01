<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML Strict//EN">
<html>
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">	
<link type="text/css" rel="stylesheet" href="{{$smarty.const.EXTHOME}}login/css/base.css">
<link type="text/css" rel="stylesheet" href="{{$smarty.const.EXTHOME}}login/css/login.css">
</head>
<body>
<div id="login-box">
   <div class="login-top"><a href="./" target="_blank" title="返回网站主页">返回网站主页</a></div>
   <div class='safe-tips'>{{$error|default:"请输入用户名和密码"}}</div>   <div class="login-main">
    <form name="form1" method="post" action="/login/check">
      <dl>
	   <dt>用户名：</dt>
	   <dd><input type="text"  name="u" id='u' value=''/></dd>
	   <dt>密&nbsp;&nbsp;码：</dt>
	   <dd><input type="password" class="alltxt" name="p" value=''/></dd>
	   	 <dt>验证码：</dt>
	   <dd><input id="vdcode" type="text" name="validate" style="text-transform:uppercase;"/><img id="vdimgck" align="absmiddle" onClick="this.src=this.src+'?'" style="cursor: pointer;" alt="看不清？点击更换" src="/vdimgck"/>
           <a href="#" onClick="changeAuthCode();">看不清？ </a></dd>
				<dt>&nbsp;</dt>
		<dd><button type="submit" name="sm1" class="login-btn" onclick="this.form.submit();">登录</button></dd>
	 </dl>
	</form>
   </div>
   <div class="login-power">Designed by<a href="http://www.uxll.com" title="uxll.com"><strong>Uxll</strong></a> | Ver:{{$smarty.const.UXLL_VERSION}}</div>
</div>	
	
	

<script>
window.onload  = function(){
	document.getElementById('u').focus();	
}
function changeAuthCode () {
	document.getElementById('vdimgck').src=document.getElementById('vdimgck').src+'?';
}	
</script>
</body></html>