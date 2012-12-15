<div style="margin:50px auto;width:500px;">
<fieldset>
<legend>同步设置</legend>
<form action="/privilege/resynchronicsetting" method="post">
要连接的网址(后面不要带/)<br><input type="text" name="url" value="{{$resynchronicurl}}" size="50">
<br>
通信密码:(上面网址那个站设置的通信密码)<br><input type="text" name="apikey" value="{{$connectkey}}" size="20">
<input type="submit" value="保存">
</form>

</fieldset>
<br>

<fieldset>
<legend>网站同步</legend>
<form action="/privilege/resynchronicDo" method="post" id="resynchronicform">
<h3>数据库部分</h3>
<input type="checkbox" name="resynchronictype_db_artical">文章同步
<input type="checkbox" name="resynchronictype_db_channel">频道同步
<input type="checkbox" name="resynchronictype_db_comment">评论同步
<input type="checkbox" name="resynchronictype_db_feedback">留言同步<br>
<input type="checkbox" name="resynchronictype_db_keywords">网页标题，描述等同步
<input type="checkbox" name="resynchronictype_db_preference">模板变量，电话等同步<br>
<input type="checkbox" name="resynchronictype_db_systemuser">系统用户同步
<h3>文件部分</h3>
<input type="checkbox" name="resynchronictype_file_template">模板同步
<input type="checkbox" name="resynchronictype_file_uploadimages">上传图片同步
<input type="text" size="2" name="resynchronictype_setting_updatetime" value="12">最后多少小时修改的文件
<hr>
<input type="submit" value="开始同步" id="resynchronicbtn">
<br>
<br>
小提示:
<br>
“最后多少小时修改的文件”是同步最近多少小时修改的文件，这样只同步刚上传过的，或者刚修改过的图片，这样速度更快
</form>


</fieldset>
</div>
<script>
$(function(){
	$("#resynchronicform").submit(function(){
		if(!confirm('你小心点，不要点错了，如果点错了，现在点否')){
			return false;
		}
		$("#resynchronicbtn").val("正在同步，可能时间也点长，请等待...").attr("disabled",true);
	});
	$("input[name=resynchronictype_db_preference]").click(function(){
		if(this.checked){
			alert("此项被选中，你的电话也会被同步，请注意!");
		}
	
	});
});

</script>