<?php
return array(
		"update" => array(
			'datawrap' => array(
				'password' => "systemuserconfig::updatepassword"
			)	
			,'patterns' => array(
				'name' => "/^[a-z\d]+$/"
			)				
		)
		,"append" => array(
			'datawrap' => array(
				'password' => "systemuserconfig::updatepassword"
			)	
			,'patterns' => array(
				'name' => "/^[a-z\d]+$/"
			)				
		)

);