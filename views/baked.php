<?php
class BakedView extends View {

	/**
	* Content Items
	*/
	function content($name, $type = null, $options = null) {
		echo $this->viewVars['node']['Node'][$name];
	}
	function image($name, $options = null) {
		echo $this->viewVars['node']['Node'][$name];
	}
	function textarea($name, $options = null) {
		echo $this->viewVars['node']['Node'][$name];
	}
	function wysiwyg($name, $options = null) {
		echo $this->viewVars['node']['Node'][$name];
	}

	/**
	* Global Content
	*/
	function shared() {
		echo 'sup';
	}
}
?>