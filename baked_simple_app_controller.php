<?php
class BakedSimpleAppController extends AppController 
{
	var $helpers = array('Html', 'Form', 'Javascript', 'Uniform');
	var $components = array('Auth');
	
	function admin_save_order($id = null) 
	{
    	if ( !$id ) {
			die('changed row missing');
    	}
    	if ( !is_numeric($id) ) {
			die('changed row isnt a number');
    	}
    	if ( !isset($this->data[$this->modelClass]['ordering']) ) {
			die('ordering isnt set');
    	}
    	if ( !is_numeric($this->data[$this->modelClass]['ordering']) ) {
    		die('ordering isnt numberic');
    	}
    	
    	die(json_encode($this->{$this->modelClass}->save($this->data, true, array('ordering'))));
	}
}
?>
