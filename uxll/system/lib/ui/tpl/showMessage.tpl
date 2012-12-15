<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">
<HTML>
<HEAD>
<title>Uxll php library redirect page</title>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<style>
#redirect_time_decrease{
	font:bold 15px/24px arial;
	text-decoration:undeline;
	color:green;	
	border:0px solid gray;
}
a{
	font:normal 15px/24px arial;
	text-decoration:undeline;
	color:blue;
}
</style>
</HEAD>
<body style="margin: 0px">
<table cellspacing=0 cellpadding=0 width=563 align=center border=0>
<tbody>
<tr><td height=125 align=center><h2>
{{if $state|regex_replace:"/\/\d$/":"" eq "failed"}}
{{$language.failed|capitalize}}
{{elseif $state|regex_replace:"/\/\d$/":"" eq "successful"}}
{{$language.success|capitalize}}
{{else}}
{{/if}}
</h2></TD></TR>
<TR>
	<TD bgColor="#ffffff" height="195" style="border:1px solid gray;box-shadow:0 0 2px #aaa">
		<TABLE cellSpacing=0 cellPadding=0 align=center border=0 width="90%">
		<TBODY>
		<TR>
			<TD align="center">
				<a href="{{$smarty.const.HOME}}" title="{{$language.back_to}} {{$language.home}}">
					<img src='{{$smarty.const.SYSUIHOME}}img/showMessage/{{$state|default:"error"}}.gif' border=0>
				</a>
				
			</TD>
			<TD style="FONT:normal 15px/30px arial; COLOR: #02095f;padding-left:25px;text-align:left">
				{{$msg}}
			</TD>
		</TR>
		<TR>
			<TD COLSPAN='2' style="FONT:normal 12px/30px arial; color: #888888"><br>{{eval var=$redirect assign=test}}{{$test}}</TD>
		</TR>
		</TBODY>
		</TABLE>
	</TD>
</TR>
<tr>
	<td align="center">
		<BR>
		{{if is_array($emergency)}}
		
		<a href='{{$emergency[0]}}'>{{$emergency[1]}}</a>
		|
		
		{{/if}}
		{{$language.back_to}} <a href='{{$smarty.const.HOME}}'>{{$language.home}}</a>
		
	</td>
</tr>
</TBODY>
</TABLE>
<SCRIPT>
function jump(x){
	var d = document.getElementById('redirect_time_decrease');
	d.innerHTML = x;
	if(x === 0){
		var url = '{{$url}}';
		if(url===''){
			history.go(-1);
		}else{
			location.href = "{{$url}}";
		}
		
	}else{
		x--;
		setTimeout(function(){jump(x)},1000)
	}
}
jump({{$delay}});
</SCRIPT>
</BODY></HTML>
