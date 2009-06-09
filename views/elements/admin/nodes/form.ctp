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
	echo $uniform->input('title', array('class' => 'textInput title'));
	echo $uniform->input('menu_title');
	echo $uniform->input('parent_id', array('empty' => '- No Parent -'));
	echo $uniform->input('type');
	echo $uniform->input('slug', array('after' => '<p class="formHint">Slug will be control what URL this content will be available from</p>'));
	echo $uniform->input('url', array('after' => '<p class="formHint">Only for URL type</p>'));
	echo $uniform->input('aliases', array('label' => 'Node Aliases', 'type' => 'textarea', 'after' => '<p class="formHint">Node aliases allow this node to be access from different URLs. One per line. Use a MySQL Regex</p>'));
	echo $uniform->input('layout');
	echo $uniform->input('template');
	echo $uniform->input('enabled', array('checked' => isset($this->data['Node']['enabled']) ? $this->data['Node']['enabled'] : true ));
	echo $uniform->input('visible', array('checked' => isset($this->data['Node']['visible']) ? $this->data['Node']['visible'] : true ));
	echo $uniform->input('default');
?>