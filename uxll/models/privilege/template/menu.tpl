<div class="ui-state-highlight avatar">
	<div class="info">
		<a href="/privilege" title="版本:{{$smarty.const.UXLL_VERSION}}">
		{{$privilege = $ui->getprivilege()}}
		<img src="{{$ui->getThisHome()}}template/images/{{$privilege["priv"]}}.png">
		
		</a>
	</div>
</div>

<div class="topmenu ui-state-default">
	<div class="floatmenu">
		<ul class="ui-helper-clearfix">
			<li class="menuitem"><a href="/privilege/template"><span class="ui-icon ui-icon ui-icon-carat-1-s ui-button" id="menu-template"></span>模板管理</a></li>
			<li class="menuitem"><a href="/"><span class="ui-icon ui-icon-home ui-button"></span>网站首页</a></li>
			<li class="menuitem"><a href="/privilege/channel"><span class="ui-icon ui-icon-tag ui-button"></span>频道管理</a></li>
			<li class="menuitem"><a href="/privilege/artical"><span class="ui-icon ui-icon-document ui-button"></span>文章列表</a></li>
			<li class="menuitem"><a href="/privilege/link"><span class="ui-icon ui-icon-carat-1-s ui-button" id="menu-more-link"></span>不常用链接</a></li>
			
			
			<li class="menuitem"><a href="/privilege/setting"><span class="ui-icon ui-icon-gear ui-button"></span>设置</a></li>
			<li class="menuitem"><a href="/login/logout"><span class="ui-icon ui-icon-power ui-button"></span>退出</a>
			</li>
		</ul>
		<div class="nav-menu-ft"></div>
	</div>
</div>
<div class="menu menu-template">
	<ol>
		<li><a href="/privilege/template/p/skeleton">整个框架模板</a></li>
		<li><a href="/privilege/template/p/head">头部模板</a></li>		
		<li><a href="/privilege/template/p/bottom">底部模板</a></li>
		<li><a href="/privilege/template/p/index">首页模板</a></li>
		<li><a href="/privilege/template/p/list">列表模板</a></li>
		<li><a href="/privilege/template/p/artical">文章模板</a></li>
		<li><a href="/privilege/template/p/artical-head">文章头部模板</a></li>
		<li><a href="/privilege/template/p/artical-bottom">文章尾部模板</a></li>
		<li><a href="/privilege/template/p/css">修改CSS文件</a></li>
	</ol>
</div>

<div class="menu menu-more-link">
	<ol>
		<li><a href="/privilege/reviseTemplateLocation">管理首页模板位置</a></li>
		<li><a href="/privilege/seosetting">SEO常用编辑</a></li>
		<li><a href="/privilege">后台首页</a></li>
		<li><a href="/privilege/comment">网友评论</a></li>
		<li><a href="/privilege/upload">上传图片</a></li>
		<li><a href="/privilege/viewuploadedimage">已上传图片</a></li>
		<li><a href="/privilege/passworld">修改密码</a></li>
		<li><a href="/privilege/feedback">留言管理</a></li>
		<li><a href="/privilege/refreashcache">刷新缓存</a></li>
		<li><a href="/privilege/resynchronic">网站同步</a></li>
		<li><a href="/privilege/upgrade">网站升级</a></li>
	</ol>
</div>
