<?php
return array(
	'system' => array(
		"channel" => "channelconfig::editchannel|编辑|id;channelconfig::removechannel|删除|id;articalconfig::addartical|添加文章|link"
		,"artical" => "articalconfig::edit|编辑|id;articalconfig::remove|删除|id;articalconfig::view|查看|id"
		,"comment" => "commentconfig::edit|编辑|id;commentconfig::remove|删除|id"
		,"feedback" => "feedbackconfig::edit|编辑|id;feedbackconfig::remove|删除|id;feedbackconfig::verify|验证|id,verification"
	)
	,"artical" => array(
		"channel" => "channelconfig::editchannel|编辑|id;articalconfig::addartical|添加文章|link"
		,"artical" => "articalconfig::edit|编辑|id;articalconfig::remove|删除|id;articalconfig::view|查看|id"
		,"comment" => "commentconfig::edit|编辑|id;commentconfig::remove|删除|id"
		,"feedback" => "feedbackconfig::edit|编辑|id;feedbackconfig::remove|删除|id;feedbackconfig::verify|验证|id,verification"
	)

);