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
		$url = $this->_getUrlForEavFile($value);
		return $url;
	}
	function media($name, $tab = 'Content', $args = array()) {
		$media = $this->loaded['media'];
		$value = $this->viewVars['node']['Node'][$name];
		$url = $this->_getUrlForEavFile($value);
		return $media->display($url);
	}

	/**
	* Returns a valid URL for a file type eav.
	*/
	function _getUrlForEavFile($value) {
		if ( !empty($value['dir']) ) {
			$url = '/' . $value['dir'] . '/' . $value['value'];	
		}
		else {
			$url = $value['value'];
		}
		$url = str_replace('\\', '/', $url);
		return $url;
	}


	/**
	* Global Content
	*/
	function shared($name) {
		$content = Set::extract('/Shared[title=' . $name . ']', $this->viewVars['shareds']);
		return $content[0]['Shared']['content'];
	}

	/**
	* Used to check if content is set.
	*/
	function check($name) {
		return !empty($this->viewVars['node']['Node'][$name]);
	}
}
?>