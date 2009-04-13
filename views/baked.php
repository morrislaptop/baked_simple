<?php
class BakedView extends View {
	function content($name, $type = null, $options = null) {
		echo $this->viewVars['node']['Node'][$name];
	}
}
?>