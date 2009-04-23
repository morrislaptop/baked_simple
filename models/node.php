<?php
class Node extends AppModel {

	var $name = 'Node';
	var $validate = array(
		'title' => array('notempty'),
		'type' => array('notempty')
	);
	var $actsAs = array(
		'Tree',
		'Sluggable' => array(
			'overwrite' => true
		),
		'Eav.Eav' => array(
			'alias' => 'eavModel'
		),
		'Containable'
	);


	function eavModel($template = null, $layout = null) {
		if ( empty($template) ) {
			$template = $this->quietField('template');
		}
		if ( empty($layout) ) {
			$layout = $this->quietField('layout');
		}
		$alias = $layout . '/' . $template;
		$dotPos = strrpos($alias, '.');
		if ( $dotPos !== false ) {
			$alias = substr($template, 0, $dotPos);
		}
		$alias = ucwords(str_replace(array('/', '\\'), ' ', $alias));
		$alias = str_replace(' ', '', $alias);
		return $this->alias . $alias;
	}

	function content($name, $type, $options)
	{
		$reserved = array_keys($this->schema());
		if ( in_array($name, $reserved) ) {
			$this->templateErrors[] = $name . ' is a reserved content key. Please choose another';
			return;
		}
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
		if ( parent::schema(array_pop(explode('.', $column))) ) {
			return parent::getColumnType($column);
		}
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
	function parentSchema($field = false) {
		return parent::schema($field);
	}

	// Caches a URL.
	function afterSave() {
		if ( 'Url' != $this->data['Node']['type'] ) {
			$this->saveField('url', $this->url(), array('validate' => false, 'callbacks' => false));
		}

		// get all the children as they will be affected.
		$id = $this->id;
		$children = $this->children($this->id);
		foreach ($children as $child) {
			$this->id = $child['Node']['id'];
			$this->saveField('url', $this->url(), array('validate' => false, 'callbacks' => false));
		}
		$this->id = $id;
	}

	function url() {
		$path = $this->getPath($this->id);
		$paths = Set::extract('/Node/slug', $path);
		$me = array_pop($path);
		if ( $me['Node']['default'] ) {
			array_pop($paths);
		}
		$url = '/' . join('/', $paths);
		return $url;
	}
}
?>