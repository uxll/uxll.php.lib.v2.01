{{*model\privilege\common\ui.php*}}
<!DOCTYPE HTML>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<title>后台管理</title>
{{include "./header.tpl"}}
{{$ui -> getheader()}}
</head>
<body>
{{include "./menu.tpl"}}
<div class="bodywrap">
	{{$ui -> getbodyhtml()}}
</div>

{{$ui -> getdiyjs()}}

</body>

</html>
