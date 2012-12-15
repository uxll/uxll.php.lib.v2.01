<?php
require_once(CONFIGROOT."upgrade.php");
?>
<script>
function checkupgrade(o){
	var url = $("#url-check").val();
	var ps = $("#ps-check").val();
	var key = $("#key-check").val();
	o.disabled = true;
	$.get("/api/check.php?url="+encodeURIComponent(url+"?key="+ps)+"&key="+key).success(function(data){
		alert(data)
		o.disabled = false;
	}).error(function(){
		alert("出现错误...");
	});
}
function doupgrade(o){
	var url = $("#url-upgrade").val();
	var ps = $("#ps-upgrade").val();
	var key = $("#key-upgrade").val();
	o.disabled = true;
	$.get("/api/upgrade.php?url="+encodeURIComponent(url+"?key="+ps)+"&key="+key).success(function(data){
		$("#upgrade-info").html(data)
		o.disabled = false;
	}).error(function(){
		alert("出现错误...");
	});
}	
</script>
<div style="width:80%;margin:50px auto;">

<fieldset>
	<legend>检查升级</legend>
	没有必要，你不需要修改
	<table>
	<tr>
		<td>连接网址:</td>	
		<td><input size="70" id="url-check" value="<?php echo (UPGRADECONNECTINGURL)?>check.php"></td>
	</tr>
	<tr>
		<td>连接密码:</td>	
		<td><input id="key-check" type="hidden" value="<?php echo require(CONFIGROOT."apikey.php")?>"><input size="70" id="ps-check" value="<?php echo md5(UPGRADEAPIKEY)?>"></td>
	</tr>
	</table>
	<hr>
	<button onclick="checkupgrade(this)">检查</button>
</fieldset>
<br>
<br>
<fieldset>
	<legend>升级程序</legend>
	<div id="upgrade-info">
		没有必要，你不需要修改
		<table>
		<tr>
			<td>连接网址:</td>	
			<td><input size="70" id="url-upgrade" value="<?php echo (UPGRADECONNECTINGURL)?>upgrade.php"></td>
		</tr>
		<tr>
			<td>连接密码:</td>	
			<td><input id="key-upgrade" type="hidden" value="<?php echo require(CONFIGROOT."config/apikey.php")?>"><input size="70" id="ps-upgrade" value="<?php echo md5(UPGRADEAPIKEY)?>"></td>
		</tr>
		</table>
		<hr>
		<button onclick="doupgrade(this)">升级</button>		
	</div>
</fieldset>
</div>