<?php
	echo $html->css('forms', false, false, false);
	echo $advform->input('title');
	echo $advform->input('content', array('type' => 'wysiwyg'));
?>