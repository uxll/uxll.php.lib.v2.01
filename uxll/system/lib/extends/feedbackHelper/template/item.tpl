<table>
{{foreach $config['fields'] as $key => $field}}
<tr>
	<td class='feedback-fieldname'>{{if isset($field['value'])}}
	{{$field['value'][$field['text']]}}
	{{else}}
	{{$field['value']}}
	{{/if}}</td>
	<td>{{htmlspecialchars($item[$key])}}</td>
</tr>
{{/foreach}}
</table>