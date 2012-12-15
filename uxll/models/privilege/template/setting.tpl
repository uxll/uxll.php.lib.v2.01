
<div class="mypreference">
	<h3>系统配置</h3>
	<br>
	
	<fieldset>
		<legend>通信密码</legend>
		<form action="/privilege/setting/action/setSuperPwd" method="post">
		此密码用于网站同步，快速登陆(不用于网站升级)
		<br>
		当你忘记后台密码时，也可以用此密码登陆
		<br>
		<input size=25 name="c" value="{{$setting['superpassword']}}"><input type="submit" value="设置">
		</form>
	</fieldset>	
	
	<br>
	
	<fieldset>
		<legend>提醒邮箱</legend>
		<form action="/privilege/setting/action/setRemindEmail" method="post">
		<input size=25 name="c" value="{{$setting['remindemail']}}">
		<br>
		是否开启
		<input type="radio" name="v" value="on"{{if $setting['allowautosendemailtoremind'] === 'on'}} checked{{/if}}>开启
		<input type="radio" name="v" value="off"{{if $setting['allowautosendemailtoremind'] === 'off'}} checked{{/if}}>不开启
		<br>
		<input type="submit" value="保存">
		</form>
	</fieldset>	
	<br>
	
	<fieldset>
		<legend>短信提醒</legend>
		<form action="/privilege/setting/action/setRemindSms" method="post">
		<input size=25 name="c" value="{{$setting['remindsms']}}">
		<br>
		是否开启
		<input type="radio" name="v" value="on"{{if $setting['allowautosendsmstoremind'] === 'on'}} checked{{/if}}>开启
		<input type="radio" name="v" value="off"{{if $setting['allowautosendsmstoremind'] === 'off'}} checked{{/if}}>不开启
		<br>
		<textarea name="x" style="width:450px;">{{$setting['remindsmscontent']}}</textarea>
		<br>
		<input type="submit" value="保存">
		</form>
	</fieldset>			
	<br>
	<fieldset>
		<legend>电话号码</legend>
		<form action="/privilege/setting/action/updatetel" method="post">
		<input type="text" name="c" value="{{$setting['currentTel']}}"><input type="submit" value="保存">
		模板变量名:tel
		</form>
	</fieldset>

	<br>
	<fieldset>
		<legend>是否允许评论</legend>
		<form action="/privilege/setting/action/updateallowcomment" method="post">
		<input type="radio" name="c" value="on"{{if $setting['allowcomment'] === 'on'}} checked{{/if}}>允许
		<input type="radio" name="c" value="off"{{if $setting['allowcomment'] === 'off'}} checked{{/if}}>不允许
		
		<input type="submit" value="保存">
		</form>
	</fieldset>

	<br>
	<fieldset>
		<legend>是否显示相关文章</legend>
		<form action="/privilege/setting/action/updateallowrelativeartical" method="post">
		<input type="radio" name="c" value="on"{{if $setting['allowrelativeartical'] === 'on'}} checked{{/if}}>允许
		<input type="radio" name="c" value="off"{{if $setting['allowrelativeartical'] === 'off'}} checked{{/if}}>不允许
		
		<input type="submit" value="保存">
		</form>
	</fieldset>

	<br>
	<fieldset>
		<legend>允许自动弹出电话框</legend>
		<form action="/privilege/setting/action/updateallowautopop" method="post">
		<input type="radio" name="c" value="on"{{if $setting['allowautopop'] === 'on'}} checked{{/if}}>允许
		<input type="radio" name="c" value="off"{{if $setting['allowautopop'] === 'off'}} checked{{/if}}>不允许
		
		<input type="submit" value="保存">
		</form>
	</fieldset>

	<br>	
	<fieldset>
		<legend>列表页的列表个数</legend>
		<form action="/privilege/setting/action/updatelistnumofpage" method="post">
		<input type="text" name="c" value="{{$setting['listnumofpage']}}"><input type="submit" value="保存">
		</form>
	</fieldset>
	
	<br>
	
	<fieldset>
		<legend>自定义我的工具栏</legend>
		先到 <a href="http://ueditor.baidu.com/website/ipanel/panel.html" target="_blank">这里去自定义</a>
		如果你搞错了，请点击这里用我默认的 <a href="/privilege/setting/action/restoreueditortoolbar" class='restore' onclick="return confirm('确认要恢复吗?')">恢复</a>
		<br>
		然后下载压缩包,这里只需要里面的两个文件<br>
		<form action="/privilege/setting/action/ueditortoolbar" method="post">
		压缩包根目录下的 <b>editor_config.js</b> 文件内容
		<textarea name="js">{{$setting['toolbar']['js']}}</textarea>
		<br>
		压缩包根目录下的<b>/ themes / default / ueditor.css</b>文件内容
		<textarea name="css">{{$setting['toolbar']['css']}}</textarea>
		<br>
		<input type="submit" value="更新">
		</form>
	</fieldset>
	
	<br>

	<br>
	<h3>自定义变量</h3>

	{{foreach $setting['diyvars'] as $diyvar}}

		<fieldset>
			<legend>{{$diyvar['v']}}</legend>
			<form action="/privilege/setting/action/updatediyvar?i={{$diyvar['id']}}" method="post">
			模板变量英文名 : <input type="text" value="{{$diyvar['k']}}" name="k">
			<br>
			已被使用的模板变量列表:<br>'rs', 'html', 'control','control_text','page','comment'<br>
			{{foreach $setting['registeredVar'] as $tvar}}
			{{if $tvar != $diyvar['k']}}
			<span class='registed-templatevar'>{{$tvar}}</span>
			{{/if}}
			{{/foreach}}
			<br>
			模板变量中文名 : <input type="text" value="{{$diyvar['v']}}" name="v">
			<br>
			你的内容:(这里面只支持电话模板变量tel)<textarea name="c">{{$diyvar['c']}}</textarea><input type="submit" value="保存">
			</form>
		</fieldset>	
	
	{{/foreach}}
		<fieldset>
		<legend>后台主题</legend>
		<form action="/privilege/setting/action/settheme" method="post">
		<input type="hidden" value="theme" name="k">
		<select name="c">
		{{foreach $setting['themes'] as $theme}}
		<option value="{{$theme}}"{{if $setting['currentTheme'] === $theme}} selected{{/if}}>{{$theme}}</option>
		{{/foreach}}
		</select><input type="submit" value="设置">
		</form>
	</fieldset>
</div>