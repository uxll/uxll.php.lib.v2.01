$(function () {
	$("div.template-editor>a").click(function () {
		var t = $(this).prevAll("input[name=tpl]").val();
		var n = $(this).prevAll("input[name=name]").val();
		var c = $(this).prevAll("textarea").val();
		$(this).addClass("ui-state-disabled").find("span").text("正在保存中...");
		var btn = this;
//alert("/privilege/savetemplate?p="+encodeURIComponent(t)+"&n="+n);
		$.post("/privilege/savetemplate?p="+encodeURIComponent(t)+"&n="+n,"c="+encodeURIComponent(c),function (data) {
			$(btn).removeClass('ui-state-disabled').find("span").text("保存更新");
			alert(data);
		});
	});
});