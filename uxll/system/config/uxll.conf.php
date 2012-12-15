<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: uxll.conf.php
 */
return array(
	'language' => 'cn',
	'action_not_found' => 'uxllautocall',
	'request' => array(
		'URL_MODE' => 'miscellaneous',
		'URL_GET_CONTROLLER_NAME' => 'uxllcontrol',
		'URL_GET_ACTION_NAME' => 'uxllaction',
		'URL_DEFAULT_PAGE_NAME' => 'index.php',
		'DEFAULT_CONTROLLER_NAME' => 'main',
		'DEFAULT_ACTION_NAME' => 'welcome'
	),
	'dir' => array(
		'compile_dir' => ROOT.'dc/runtime/',
		'cache_dir' => ROOT.'dc/cache/'
	),
	'auth' => array(
		'id' => 'authorization_user_id'// for session
	),
	'msg_rewrite' => CONFIGROOT.'msgRewrite.php'
);
