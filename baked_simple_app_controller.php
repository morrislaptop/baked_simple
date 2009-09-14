<?php
class BakedSimpleAppController extends AppController
{
	var $helpers = array('Html', 'Form', 'Javascript', 'Advform.Advform');
	var $components = array('Auth');
	
	function beforeFilter() {
		if ( !empty($this->params['prefix']) ) {
			$this->view = 'theme';
			$this->theme = $this->params['prefix'];
		}
		parent::beforeFilter();
	}

}
?>