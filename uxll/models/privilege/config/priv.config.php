<?php
//如果MASK写的不对，就会什么反应都没有
return array(
	'system' => array(
		'keywords' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => "all"
			,"view" => "all"
		),	
		'comment' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => "all"
			,"view" => "all"
		),	
		'systemuser' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => "all"
			,"view" => "all"
		),
		'channel' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => array("id","viewmode","location","order","pic","text","link")
			,"view" => "all"
		),
		'artical' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => array("id","nocomment","order","channel","title","norelative","urlkey","time")
			,"view" => "all"
		),
		'feedback' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => "all"
			,"view" => "all"
		)
	),
	'artical' => array(
		'keywords' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => "all"
			,"view" => "all"
		),	
		'comment' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => "all"
			,"view" => "all"
		),	
		'systemuser' => array(
			"add" => 0
			,"append" => 0
			,"edit" => array("id","name","displayname","password")
			,"update" => array("id","displayname","password")
			,"remove" => false
			,"display" => array("id","priv","name","displayname")
			,"view" => "all"
		),
		'channel' => array(
			"add" => 0
			,"append" => 0
			,"edit" => array("id","viewmode","location","order")
			,"update" => array("id","viewmode","location","order")
			,"remove" => false
			,"display" => array("id","viewmode","location","order")
			,"view" => "all"
		),
		'artical' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => array("id","nocomment","order","channel","title","norelative")
			,"view" => "all"
		),
		'feedback' => array(
			"add" => "all"
			,"append" => "all"
			,"edit" => "all"
			,"update" => "all"
			,"remove" => true
			,"display" => "all"
			,"view" => "all"
		)
	)	
);