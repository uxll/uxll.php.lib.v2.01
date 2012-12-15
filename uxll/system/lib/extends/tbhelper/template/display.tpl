{{*tbhelper/common/ui/display.php*}}

{{assign var=rowExtraMask value=array()}}
{{assign var=allconfig value=$ui -> getAllFieldsConfig()}}

{{assign var=manifestmask value=$ui -> getManifestMask()}}
{{assign var=extramask value=$ui -> getExtraMask()}}
{{assign var=displaymask value=$ui -> getDisplayMask()}}
{{assign var=fieldurls value=$ui -> geturl()}}

{{$ui -> debug('dd')}}

<table class="jquerytheme" align='center' cellpadding="0" cellspacing="0">
<caption><h1>{{$ui -> getCaption()}}</h1></caption>
<thead>
	<tr>
		<td colspan="{{$ui -> getColspan()}}">
			<div class="topbuttoncontainer">
				<span class="topbutton">
					<input align="absmiddle" type="image" src="/lib/uxll/extends/tbhelper/template/images/converse.png"> 反选(ESC)
				</span>
				<span class="topbutton">
					<input align="absmiddle" type="image" src="/lib/uxll/extends/tbhelper/template/images/orderby.png"> 排序(Z)
				</span>
				{{$privtext = $ui -> getPrivMaskText()}}
				{{foreach $ui -> getPrivMask() as $priv}}
				<span class="topbutton">
					<a href="{{$fieldurls[$priv]}}">
					<input align="absmiddle" type="image" src="/lib/uxll/extends/tbhelper/template/images/{{$priv}}.png"> {{$privtext[$priv]}}
					</a>
				</span>
				{{/foreach}}
				
				<form action="{{$fieldurls['search']}}" method="get">
				<input name="q" value="{{if $ui -> getLastQueryString()}}{{$ui -> getLastQueryString()}}{{else}}pk:12{{/if}}"><input type="submit" value="search">
				</form>
				<a href="/help">?</a>
				支持:string | time | number | sql | pk | enum | img
			</div>
		</td>
	</tr>
	<tr>
		{{foreach $ui -> getFieldsDisplayName() as $thk => $th}}
		{{if (in_array($thk,$displaymask))}}
		<th>
			<span class="thead-orderby" title="{{$thk}}"{{if $ui -> getPrimaryKey() === $thk}} id="tbhelper-thead-pk"{{/if}}>{{$th}}</span>
		</th>
		{{/if}}
		{{/foreach}}
		{{if $ui -> getExtraFields()}}
		<th>
			操作
		</th>				
		{{/if}}				

	</tr>
</thead>
<tbody>
{{*assign var=cur value=0*}}
{{$FieldsManifest=$ui -> getFieldsManifest()}}





{{foreach $ui -> getRsBody() as $tr}}	
{{assign var=rowExtraMask value=array()}}
	<tr>
		{{foreach $tr as $field => $td}}
		{{if (in_array($field,$displaymask))}}
		{{*
			这里应该存在于配置文件的FieldsManifest
			要区分FieldsManifest要处理的字段和参数
			像这里的manifestmask是参数最大数组
		*}}
			{{if (array_key_exists($field,$FieldsManifest))}}
				<td>{{$ui -> displayManifestFields($field,$tr)}}</td>
			{{else}}
				<td>{{$ui -> displayDefaultFields($field,$tr)}}</td>
			{{/if}}
		{{/if}}
		{{/foreach}}	
		{{if $ui -> getExtraFields()}}
		<td>
			<span class="tbhelper-operation">{{$ui -> displayExtraFields($tr,$extramask,$allconfig['extra'])}}</span>
		</td>
		{{/if}}	
		
	</tr>
{{/foreach}}
</tbody>
{{if ($ui -> getPageHTML())}}
<tfoot>
<tr>
<td colspan="{{$ui -> getColspan()}}">
{{$ui -> getPageHTML()}}
</td>
</tr>
</tfoot>
{{/if}}
</table>
{{$ui -> getExtraHTML()}}