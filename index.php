<?php
/**
 *  @version: 2.01
 *  @date: 2012-12-13 
 *  @author: uxll@qq.com
 *  @file: index.php
 */
require('uxll/system/config/const.php');
require(ROOT.'uxll/system/lib/uxll.lib.php');
R('CONTROL') -> route(new CMessage(R('HTTP') -> getMessage()));