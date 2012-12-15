{{if $total.total != 1}}
<span class="divpage">
	<span class="total">{{$total.start}} {{$total.of}} {{$total.total}}</span>
	<span class="pagelist">
		{{if $previous.url != ""}}
			<a href='{{$previous.url}}'>{{$previous.text}}</a>
		{{/if}}
		{{foreach from=$pages item=url key=page}}
			{{if $url != ''}}
			<a href="{{$url}}">{{$page}}</a>
			{{else}}
			<a class='current'>{{$page}}</a>
			{{/if}}
		{{/foreach}}
		{{if $next.url != ""}}
			<a href='{{$next.url}}'>{{$next.text}}</a>
		{{/if}}
	</span>
	<span class="jump">
	<input onkeypress="if((window.event||event).keyCode==13)this.nextSibling.onclick.apply(this.nextSibling,[])" class="go" size="4"><a href="javascript:void(0)" onclick="if(/^\d+$/gi.test(this.previousSibling.value))window.location='{{$urlpage}}'.replace(/{{$pageseparator}}/,this.previousSibling.value);return false;">Go</a>
	</span>
</span>	
{{/if}}