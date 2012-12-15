<?php

return array(


		"display" => array(
			"fieldsmanifest" => array(
				"urlkey" => "articalconfig::readartical|lol|urlkey,channel"	
				//,"" => 
			)
		
		)
		,"view" => array(
			"fieldsmanifest" => array(
				"viewmode" => "channelconfig::viewmode|lol|viewmode"	
//				,"viewmode" => array(
//					"value" => array(
//						"yes" => "yes50",
//						"no" => "no5"
//					)
//				)
			)		
		
		)
		,"edit" => array(
			"fieldsmanifest" => array(
				"template" => array(
					"extravalue" => array(
						"cols" => "50",
						"rows" => "5"
					)
				)	
			)		
			,"fieldsdisplayname" => array(
				"html" => "文章内容
					<br>
					<a href='#' onclick='start_ueditor_for_artical(this);return false;'>启用百度在线编辑</a>
					<br>
					<a href='#' onclick='destroy_ueditor_for_artical(this);return false;'>移除百度在线编辑</a>
					<br>
					<a href='#' onclick='start_ckeditor_for_artical(this);return false;'>启用CKEDITOR</a>
					<br>
					<a href='#' onclick='destroy_ckeditor_for_artical(this);return false;'>移除CKEDITOR</a>
					<br>					
					<a href='#' onclick='auto_align(this);return false;'>排版助手</a>
					
					"
			)
		)
		
		,"add" => array(
			"fieldsdisplayname" => array(
				"html" => "文章内容
					<br>
					<a href='#' onclick='start_ueditor_for_artical(this);return false;'>启用百度在线编辑</a>
					<br>
					<a href='#' onclick='destroy_ueditor_for_artical(this);return false;'>移除百度在线编辑</a>
					<br>
					<a href='#' onclick='start_ckeditor_for_artical(this);return false;'>启用CKEDITOR</a>
					<br>
					<a href='#' onclick='destroy_ckeditor_for_artical(this);return false;'>移除CKEDITOR</a>
					<br>					
					<a href='#' onclick='auto_align(this);return false;'>排版助手</a>
					"
			)			
		
		)
	

);