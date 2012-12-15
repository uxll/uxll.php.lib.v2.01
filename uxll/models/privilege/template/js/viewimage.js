$(function () {
	$("#viewupload-reverse").click(function () {
		$("div.viewuploadimage input[type=checkbox]").each(function () {
			this.checked = !this.checked;
		});
	});
	$("#viewupload-remove").click(function () {
		if(!confirm("确认要删除选中项吗?"))return false;
		$("div.viewuploadimage input[type=checkbox]").each(function () {
			if(this.checked ){
				var me = this;
				$.get("/privilege/removeuploadimg?p="+encodeURIComponent(this.value),function (data) {
					if(data === "true"){
						$(me).parent().remove();
					}else{
						alert(me.value + "删除失败\n"+data);
					}
				})
			};
		});
	});
	$("#viewupload-geturl").click(function () {
		var url = [];
		$("div.viewuploadimage input[type=checkbox]").each(function () {
			if(this.checked ){
				url.push("<img src='"+this.value+"' width='20' height='20'>"+this.value);
			};
		});
		$("div.viewuploadimage div.geturl").html(url.join("<br>"));
	});
	$("#viewupload-replace").click(function () {
		var url = [];
		$("div.viewuploadimage input[type=checkbox]").each(function () {
			if(this.checked ){
				var p = this.value.split("/").pop();
				window.location = "/privilege/replaceuploadimageui?i="+encodeURIComponent(p)
				return false;
			};
		});
		$("div.viewuploadimage div.geturl").html(url.join("<br>"));
	});
});