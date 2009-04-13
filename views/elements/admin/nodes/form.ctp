<?php
	$session->flash();
	echo $uniform->input('parent_id', array('empty' => '- No Parent -'));
	echo $uniform->input('type');
	echo $uniform->input('layout');
	echo $uniform->input('template');
	echo $uniform->input('title');
	echo $uniform->input('enabled');
	echo $uniform->input('visible');
	echo $uniform->input('default');
?>