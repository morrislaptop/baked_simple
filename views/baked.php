<?php
class BakedView extends View {

	var $swfobject = false;

	/**
	* Content Items
	*/
	function __call($name, $args) {
		return $this->viewVars['node']['Node'][$args[0]];
	}
	function flash($name, $args = array()) {
		if ( !$this->swfobject ) {
			$this->embedSwfobject();
		}
		$id = 'flash' . intval(mt_rand());
		$js = $this->loaded['javascript'];
		$out = '<div id="' . $id . '"><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" /></a></div>';
		$out .= $js->codeBlock('
			swfobject.embedSWF("' . $this->viewVars['node']['Node'][$name] . '", "' . $id . '", "' . $args['width'] . '", "' . $args['height'] . '", "9.0.0", false);
		');
		return $out;
	}

	function embedSwfobject() {
		$js = $this->loaded['javascript'];
		$js->link('swfobject/swfobject', false);
		$this->swfobject = true;
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