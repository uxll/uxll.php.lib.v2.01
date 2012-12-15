<META http-equiv=Content-Type content="text/html; charset=utf-8">
{{*tbhelper/common/ui/edit.php*}}
{{$displayName = $ui -> getFieldsDisplayName()}}

<div class="viewform">
{{$count = count($ui -> getRows())}}


		<table class='onepix'>
		<caption>{{$ui -> getCaption()}}</caption>
		<thead>

		<tr>
		<th colspan="2">	
					<a href="{{$ui -> getLastUrl() }}">返回</a>

		</th>
		
		</tr>
		</thead>
		<tbody>
	{{foreach $ui -> getRows() as $row}}	
	{{$hiddenpk = $row[$ui -> getPrimaryKey()]}}		
		{{foreach $ui -> getAllFields() as $field}}
		<tr{{if $ui -> getFieldFlag($field)}} class="tbhelper-null-row"{{/if}}>
			<td>
					{{if array_key_exists($field,$displayName)}}
						{{$displayName[$field]}}
					{{else}}
						{{$field}}
					{{/if}}				
			</td>
			<td>
				{{$ui -> html($field,$row,$hiddenpk,"view")}}
			</td>
		</tr>

		{{/foreach}}
		<tr>
			<td colspan="2" align="center"><font color="red">---------------------------- 我是邪恶的分割线 ----------------------------</font></td>
		</tr>
	{{/foreach}}	
		</tbody>
		<tfoot>
		<tr>
			<td colspan=2>
					<a href="{{$ui -> getLastUrl() }}">返回</a>
			</td>
		</tr>
		</tfoot>
		</table>
</div>


{{$ui -> getExtraHTML()}}