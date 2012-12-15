<style>
.index_link{
	margin-top:55px;
	min-width:500px;
}
.index_link td{
	padding:15px;
	border:0px;
	text-align:center;
}
.index_link img{
	
}
.index_link a{
	font:bolder 16px/30px arial;
	text-decoration:none;
}
.html{
	list-style-type:none;
	border:1px solid lightgray;
	padding:15px;
	text-align:left;
	line-height:25px;	
	color:#777777
}
.html code{
	color:#008800;
}
.html span{
	color:#000088;
	text-decoration:underline;
}
.html i{
	color:#880000;
	
}
</style>
<table align="center" class="index_link">
<tr>
	<td colspan=3>
		常用编辑器代码:
		<ul class="html">
			<li><code><?php
					echo htmlspecialchars('<div style="text-align:center;color:red;font-weight:bolder">放内容</div>')
				?></code>
				<br>
				
				<b>text-align</b>是<i>对齐</i>可用的选项是<span>left</span>,<span>right</span>,<span>center</span><br>
				<b>color:</b><i>颜色</i>可用的选项是<span>red</span>,<span>blue</span>,<span>green</span>,<span>#ea0274</span> ...<br>
				<b>font-weight</b><i>字体加粗</i>:可用的选项是<span>normal</span>,<span>bold</span>,<span>bolder</span><br>
				<b>text-decoration</b><i>字体加下划线</i>:可用的选项是<span>none</span>,<span>underline</span>,<span>line-through</span>
				<br><code>图片:
				<?php
					echo htmlspecialchars('<img src="/xx/oo.jpg">')
				?></code>
				<br><code>链接:
				<?php
					echo htmlspecialchars('<a href="/login/logout">')
				?></code>
				<br><code>换行:
				<?php
					echo htmlspecialchars('<br/>')
				?></code>
			</li>
			
			<li><hr>
				这次改版的一个重点就是加了自定义模板变量，它在设置菜单下<br>
				使用说明:你可以在文章中无限次使用<u>{{$模板变量名}}</u><br>
				电话代码:{{$tel}},点击打电话:<?php
					echo htmlspecialchars('<a href="tel:{{$tel}}">点我打电话</a>')
				?>
				
				
			</li>
		</ul>
		2012-7-17 17:17:30
	</td>	
</tr>
</table>

