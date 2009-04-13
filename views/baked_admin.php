<?php
class BakedAdminView extends View {
	var $templateFields = array();
	function content($name, $type = null, $options = null) {
		$this->templateFields[] = compact('name', 'type', 'options');
	}
}
?>