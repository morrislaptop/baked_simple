<?php
	$session->flash();
	echo $uniform->input('title');
	echo $uniform->input('content', array('type' => 'wysiwyg'));
?>