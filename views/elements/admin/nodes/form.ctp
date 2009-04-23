<?php
	$session->flash();
	echo $uniform->input('parent_id', array('empty' => '- No Parent -'));
	echo $uniform->input('type');
	echo $uniform->input('url');
	echo $uniform->input('layout');
	echo $uniform->input('template');
	echo $uniform->input('title');
	echo $uniform->input('enabled', array('checked' => isset($this->data['Node']['enabled']) ? $this->data['Node']['enabled'] : true ));
	echo $uniform->input('visible', array('checked' => isset($this->data['Node']['visible']) ? $this->data['Node']['visible'] : true ));
	echo $uniform->input('default');
?>