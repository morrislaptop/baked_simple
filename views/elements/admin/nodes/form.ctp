<?php
	$javascript->codeBlock('
		$(function() {
			$("#NodeTitle").change(function() {
				var menuTitle = $("#NodeMenuTitle")
				if ( !menuTitle.attr("value") ) {
					menuTitle.attr("value", this.value);
				}
			});
		});
	', array('inline' => false));

	$session->flash();
	echo $uniform->input('title', array('class' => 'title'));
	echo $uniform->input('menu_title');
	echo $uniform->input('parent_id', array('empty' => '- No Parent -'));
	echo $uniform->input('type');
	echo $uniform->input('url', array('label' => 'Url (only editable for Url Type)'));
	echo $uniform->input('aliases', array('label' => 'Node Aliases', 'type' => 'textarea', 'after' => '<p class="formHint">Node aliases allow this node to be access from different URLs. One per line. Use a MySQL Regex</p>'));
	echo $uniform->input('layout');
	echo $uniform->input('template');
	echo $uniform->input('enabled', array('checked' => isset($this->data['Node']['enabled']) ? $this->data['Node']['enabled'] : true ));
	echo $uniform->input('visible', array('checked' => isset($this->data['Node']['visible']) ? $this->data['Node']['visible'] : true ));
	echo $uniform->input('default');
?>