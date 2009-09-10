<?php
class BakedAdminView extends View {
	var $templateFields = array();

	// Core
	function content($name, $type = null, $tab = 'Content', $options = null) {
		if ( !isset($this->templateFields[$tab]) || !is_array($this->templateFields[$tab]) ) {
			$this->templateFields[$tab] = array();
		}
		$this->templateFields[$tab][] = compact('name', 'type', 'options');

		// return the data since we have it anyway, it can be used for repeater fields!
		if ( isset($this->data['Node'][$name]) ) {
			return $this->data['Node'][$name];
		}
	}
	/**
	* @param mixed $name Type of content field.
	* @param mixed $args[0] Name of content field
	* 					[1] Tab to appear in
	* 					[2] Misc options
	*/
	function __call($name, $args) {
		if ( !isset($args[1]) ) {
			$args[1] = 'Content';
		}
		if ( !isset($args[2]) ) {
			$args[2] = array();
		}
		return $this->content($args[0], $name, $args[1], $args[2]);
	}

	// Compatibility with BakedView
	function shared() { }
	function check() { return true; }
	function findChildren() { }

/**
 * Never render the missing view page, allow the user to select a different template...
 *
 * @param string $viewFileName the filename that should exist
 * @return cakeError
 */
	function _missingView($file, $error = 'missingView') {

	}
}
?>