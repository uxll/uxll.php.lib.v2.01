<form action="{{$config['action']}}" method="post" onsubmit="return {{$config['onsubmit']}}">
<table>
<caption>{{$config['caption']}}</caption>
{{foreach $config['fields'] as $field}}
<tr>
	<td>{{$field['text']}}</td>
	<td>{{$fieldsTypeUI -> getHTML("add",$field['type'],$field['extraData'])}}</td>
</tr>
{{/foreach}}
<tr>
	<td colspan="2"><input type="submit" value="提交"></td>
</tr>
</table>

</form>