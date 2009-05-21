<?php
class BakedView extends View {

	/**
	* Content Items
	* $name = Type of content field
	* $args[0] = Name of content field
	* $args[1] = Name of tab to put it in in Admin
	* $args[2] = Misc options
	*/
	function __call($name, $args) {
		return $this->viewVars['node']['Node'][$args[0]];
	}
	function image($name, $tab = 'Content', $args = array()) {
		$value = $this->viewVars['node']['Node'][$name];
		$value = str_replace('\\', '/', $value); // correct windows stupid DS for web
		return '/' . $value;
	}
	function flash($name, $tab = 'Content', $args = array()) {
		$media = $this->loaded['media'];
		return $media->display('/' . $this->viewVars['node']['Node'][$name]);
	}


	/**
	* Global Content
	*/
	function shared($name) {
		$content = Set::extract('/Shared[title=' . $name . ']', $this->viewVars['shareds']);
		echo $content[0]['Shared']['content'];
	}

	/**
	* Used to check if content is set.
	*/
	function check($name) {
		return !empty($this->viewVars['node']['Node'][$name]);
	}
}
?>