{{*tbhelper/common/ui/edit.php*}}
{{$displayName = $ui -> getFieldsDisplayName()}}

<div class="editform">
{{$count = count($ui -> getRows())}}
{{$cur = 0}}
		<form action='{{$ui -> getUpdateUrl()}}' method='{{$ui -> getMethod()}}' {{if !($ui -> allowedAjax())}}enctype="multipart/form-data"{{/if}}>
		<table class='onepix'>
		<caption>{{$ui -> getCaption()}}</caption>
		<thead>

		<tr>
		<th colspan="2"><!--
		{{if $ui -> allowedAjax()}}<div class="ajax">无刷提交:<input id='ajax_process_mode' type="checkbox"></div>{{/if}}
		<div class='validateTips'></div>
		-->
		{{if $count > 1}}
		<input type='submit' value="{{$ui -> getSubmitBtnText()}}" class="submit">
		{{/if}}
		</th>
		
		</tr>
		</thead>
		<tbody>
	{{foreach $ui -> getRows() as $row}}	
	{{$hiddenpk = $row[$ui -> getPrimaryKey()]}}		
		{{foreach $ui -> getAllFields() as $field}}
		{{if $field !== $ui -> getPrimaryKey()}}
		<tr{{if $ui -> getFieldFlag($field)}} class="tbhelper-null-row"{{/if}}>
		
			{{if array_key_exists($field,$ui -> getUpdateDescription()) }}
				<td>
						{{if array_key_exists($field,$displayName)}}
							{{$displayName[$field]}}
						{{else}}
							{{$field}}
						{{/if}}
				</td>	
				<td>
					
					{{$ui -> html($field,$row[$field],$hiddenpk)}}
				</td>
		
			{{else}}
			
				<td>
						{{if array_key_exists($field,$displayName)}}
							{{$displayName[$field]}}
						{{else}}
							{{$field}}
						{{/if}}				
				</td>
				<td>
					{{$ui -> html($field,$row[$field],$hiddenpk,"view")}}
				</td>
			
			{{/if}}
		</tr>
		{{else}}
		<tr><td>PK:</td><td>{{$hiddenpk}}<input type="hidden" name="{{$ui -> getPrimaryKey()}}[{{$hiddenpk}}]" value="{{$hiddenpk}}"></td>
			
		</tr>
		{{/if}}{{*if $field !== $ui -> getPrimaryKey()*}}
		{{/foreach}}
		<tr>
			<td colspan="2" align="center">
			{{$cur = $cur +1}}
			{{if $cur < $count}}
			<font color="red">---------------------------- 我是邪恶的分割线 ----------------------------</font>
			{{/if}}
			</td>
		</tr>
	{{/foreach}}	
		</tbody>
		<tfoot>
		<tr>
			<td colspan=2>
				{{*if $count === 1*}}
					<!--<div style="position:fixed;bottom:10px;left:36%;">-->
					<input type='submit' value="{{$ui -> getSubmitBtnText()}}" class="submit">
					<!--</div>-->
				{{*else*}}
					
				{{*/if*}}
				
			</td>
		</tr>

		</tfoot>
		</table>
		
		</form>



</div>


<script>
$(function(){$(".editform>form input[type=text]:first").focus();});	
</script>
{{$ui -> getExtraHTML()}}