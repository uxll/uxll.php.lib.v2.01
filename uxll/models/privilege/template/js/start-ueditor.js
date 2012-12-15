function start_ueditor_for_artical(o) {
	if(!window.ueditor){
		window.ueditor = {};
	}	
	var id = $(o).parent().parent().find("td:last>textarea[name^=html]");
	if(typeof ueditor[id.attr("name")] !== "undefined")return false;

	window.ueditor[id.attr("name")] = new baidu.editor.ui.Editor();
	ueditor[id.attr("name")].render(id[0]);
	ueditor[id.attr("name")].setContent(id.val());
}
function destroy_ueditor_for_artical(o) {
	var text = $(o).parent().parent().find("textarea[name^=html]");
	text.show().val(ueditor[text.attr("name")].getContent());
	
	ueditor[text.attr("name")].destroy();
	delete(ueditor[text.attr("name")]);
}



function start_ckeditor_for_artical(o) {
	if(!window.ckeditor){
		window.ckeditor = {};
	}	
	var id = $(o).parent().parent().find("td:last>textarea[name^=html]");
	if(typeof ckeditor[id.attr("name")] !== "undefined")return false;

	window.ckeditor[id.attr("name")] = CKEDITOR.replace( id[0] );;

	ueditor[id.attr("name")].setDate(id.val());
}
function destroy_ckeditor_for_artical(o) {
	var text = $(o).parent().parent().find("textarea[name^=html]");
	text.show().val(ckeditor[text.attr("name")].getData());
	
	ckeditor[text.attr("name")].destroy();
	delete(ckeditor[text.attr("name")]);
}





function auto_align(o) {
	
	var text = $(o).parent().parent().find("textarea[name^=html]");
	if(
		(window.ueditor && window.ueditor[text.attr("name")])
		||
		(window.ckeditor && window.ckeditor[text.attr("name")])
		
	){
		alert("这个功能用于你第一次用排版助手复制过来的东西，而且在初始状态下可以使用");
		return false;
	}
	text.val(function  (i,v) {
		return v.replace(/\r?\n/g,"<br/>");
	});
}