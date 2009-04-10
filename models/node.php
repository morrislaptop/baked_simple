<?php
class Node extends AppModel {

	var $name = 'Node';
	var $validate = array(
		'title' => array('notempty'),
		'slug' => array('alphanumeric'),
		'type' => array('notempty')
	);
	var $actsAs = array(
		'Tree',
		'Bakedsimple.Sluggable',
		'Eav.Eav' => array(
			'alias' => 'eavModel'
		)
	);
	
	var $templateFields = array();

	function syncEavAttributes()
	{
		if ( empty($this->data['Node']['template']) ) {
			$template = $this->field('template');
		}
		else {
			$template = $this->data['Node']['template'];
		}
		$path = VIEWS . $template;
		
		// include the file, which will be calling field() methods. Since it is being
		// called within this class, then we can automatically create attributes in the 
		// eav system (if not already there). 
		ob_start();
		include($path);
		ob_end_clean();
		
		// bind our model to the attributes table so we can query it.
		$this->bindModel(array('hasMany' => array('EavAttribute')));
		
		// get what the alias would be based on the template.
		$eavModel = $this->eavModel($template);
		
		// go through each field called and setup page attributes.
		foreach ($this->templateFields as $attribute) 
		{
			// reset so we don't update previously found attributes.
			$this->EavAttribute->create();
			
			// check if one exists.
			$conditions = array(
				'name' => $attribute['name'],
				'model' => $eavModel
			);
			$eav_attribute = $this->EavAttribute->find('first', compact('conditions'));
			if ( $eav_attribute ) {
				$this->EavAttribute->id = $eav_attribute['EavAttribute']['id']; // cause an update
			}
			
			// save or update, yay!
			$attribute['model'] = $eavModel;
			$this->EavAttribute->save($attribute);
		}
		
		return $this->templateFields;
	}
	
	function eavModel($template = null) {
		if ( empty($template) ) {
			$template = $this->quietField('template');
		}
		$alias = substr($template, 0, strrpos($template, '.'));
		$alias = ucwords(str_replace(array('/', '\\'), ' ', $alias));
		$alias = str_replace(' ', '', $alias);
		return $this->alias . $alias;
	}
	
	function content($name, $type, $options)
	{
		$this->templateFields[] = array(
			'name' => $name,
			'type' => $type,
			'options' => $options
		);
	}
	
	/**
	* Transfers rights to the EAV behavior
	* 
	* @param mixed $column
	*/
	function getColumnType($column) {
		$eavType = $this->Behaviors->dispatchMethod($this, 'getColumnType', array($column));
		if ( !$eavType || $eavType === array('unhandled') ) {
			$eavType = parent::getColumnType($column);
		}
		return $eavType;
	}
	
	function schema($field = false) {
		$eavSchema = $this->Behaviors->dispatchMethod($this, 'schema', array($field));
		if ( !$eavSchema || $eavSchema === array('unhandled') ) {
			$eavSchema = parent::schema($field);
		}
		return $eavSchema;
	}
}
?>