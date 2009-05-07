<?php
class BakedAdminView extends View {
	var $templateFields = array();
	function content($name, $type = null, $options = null) {
		$this->templateFields[] = compact('name', 'type', 'options');
	}

	function __call($name, $args) {
		$this->content($args[0], $name, empty($args[1]) ? array() : $args[1]);
	}

	function shared() { }
	function check() {
		return true; // always returns true so all possible content fields get called
	}
}
?>