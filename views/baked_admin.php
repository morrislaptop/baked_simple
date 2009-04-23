<?php
class BakedAdminView extends View {
	var $templateFields = array();
	function content($name, $type = null, $options = null) {
		$this->templateFields[] = compact('name', 'type', 'options');
	}
	function image($name, $options = null) {
		$this->content($name, 'image', $options);
	}
	function textarea($name, $options = null) {
		$this->content($name, 'textarea', $options);
	}
	function wysiwyg($name, $options = null) {
		$this->content($name, 'wysiwyg', $options);
	}

	function shared() {
		
	}
}
?>