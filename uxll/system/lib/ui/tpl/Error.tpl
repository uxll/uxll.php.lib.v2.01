<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta name="robots" content="noindex, nofollow, noarchive" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Uxll debug console</title>
<style>
#source,#info{width:800px;margin:auto;border:2px solid #eaeaea;padding:15px;}
#source span{padding:0;margin:0;}
#source #current{background:#CFF0F3;}
#info div{line-height:23px;}
#info div .title{color:blue;}
#info div .content{color:gray;}
</style>
<script>
var JSON = {
	duplicate:function(o){
		var ret={};
		if(typeof o!=='object')return false;
		for(var x in o){
			switch(o.constructor){
				case Object:
				case Array:
					ret[x]=JSON.duplicate(o[x]);	
				default:
					ret[x]=o[x];
			}
				
		}		
		return ret;
	},
	debug:function(o,format,deep,mode){
		var temp=[],ret=[];
		deep=deep||1;
		format=format||false;
		mode=mode||false;
		try{
			switch(o.constructor){
				case String:
					return '"'+(format?o.replace(/\r\n|\t/g,''):o.replace(/[\\"]/g, '\\$&').replace(/\u0000/g, '\\0'))+'"';
				case Object:
					ret.push('{');
					for(var x in o){
						temp.push(//
							(format?'\r\n'+(new Array(deep+1).join('\t')):'')+
							'"'+x.replace(/[\\"']/g, '\\$&').replace(/\u0000/g, '\\0')+'":'+
							JSON.debug(o[x],format,deep+1,mode)
						);
					}
					ret.push(temp.join(","));
					format&&ret.push((format?'\r\n':'')+(new Array(deep+1).join('\t')));
					ret.push('}');
					break;
				case Array:
					ret.push('[');
					for(var x=0;x<o.length;x++){
						temp.push((format?'\r\n'+(new Array(deep).join('\t')):'')+JSON.debug(o[x],format,deep+1,mode));
					}
					ret.push(temp.join(","));
					format&&ret.push((format?'\r\n':'')+(new Array(deep-1).join('\t')));
					ret.push(']');	
					break;
				case Function:
					if(format===true){
						if(mode===false)return 'function(){...}';
					}else{
						return 'null';	
					}
				default:
					return (o.toString());
			}
			
		}catch(e){return 'null';}
		ret = ret.join("");
		return ret;
	},

	stringify:function(o){
		return JSON.debug(o,false,1,false);
	},
	parse:function(str,showError){
		try{
			return eval('('+str+')');	
		}catch(e){showError && alert('JSON parse Error:\n'+(e.description||e.message));return false;}
	}	
};
function showtrace(){
	document.getElementById('trace').style.display = document.getElementById('trace').style.display === 'none' ? 'block':'none';
}
</script>
</head>
<body>

<center>
	<h3>UXLL PHP DEBUG INFORMATION</h3>
</center>

<div id="info">
<div><span class="title">File:</span><span class="content">{{$File}}</span></div>

<!--

<div><span class="title">Line:</span><span class="content">{{$Line}}</span></div>

-->
<div><span class="title">Message:</span><span class="content">{{$Message}}</span></div>
<div><span class="title" onclick='showtrace()' style="cursor:pointer;" title="click me to show the trace">Trace:</span><span style="display:none;" class="content" id='trace'>{{$Trace}}</span></div>
</div>
<br/>
<div id="source">
{{foreach from=$source item=curr_id}}
{{$curr_id}}<br/>
{{/foreach}}

<script>
var trace = document.getElementById('trace');
var x = JSON.parse(trace.innerHTML);
trace.innerHTML = '<pre>'+JSON.debug(x,1,true)+'</pre>';

</script>
</div>
</body>
</html>