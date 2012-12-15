<META http-equiv=Content-Type content="text/html; charset=utf-8">
{{*tbhelper/common/ui/add.php*}}
{{$displayName = $ui -> getFieldsDisplayName()}}

<div class="addform">

<form action='{{$ui -> getAppendUrl()}}' method='{{$ui -> getMethod()}}' {{if !($ui -> allowedAjax())}}enctype="multipart/form-data"{{/if}}>
<table class='onepix'>
<caption>{{$ui -> getCaption()}}</caption>
<thead>
<tr>
<th colspan="2">
{{if $ui -> allowedAjax()}}
	<!--
	<div class="ajax">无刷提交:<input id='ajax_process_mode' type="checkbox"></div>
	-->
{{/if}}
<div class='validateTips'></div>
</th></tr>
</thead>
<tbody>
{{foreach $ui -> getAllFields() as $field}}
<tr{{if $ui -> getFieldFlag($field)}} class="tbhelper-null-row"{{/if}}>

	{{if array_key_exists($field,$ui -> getAppendDescription()) }}
		<td>
				{{if array_key_exists($field,$displayName)}}
					{{$displayName[$field]}}
				{{else}}
					{{$field}}
				{{/if}}
		</td>	
		<td>
			
			{{$ui -> html($field)}}
		</td>

	{{else}}
		{{*<td>add:{{$field}}</td>*}}

	{{/if}}
</tr>
{{/foreach}}
</tbody>
<tfoot>
<tr>
	<td colspan=2><input type='submit' value="{{$ui -> getSubmitBtnText()}}"></td>
</tr>
</tfoot>
</table>

</form>
</div>
{{$ui -> getExtraHTML()}}