<?php
class Node extends AppModel {

	var $name = 'Node';
	var $validate = array(
		'title' => array('notempty'),
		'type' => array('notempty')
	);
	var $actsAs = array(
		'Tree',
		'Util.Sluggable' => array(
			'overwrite' => true,
			'group_fields' => 'parent_id',
			'group_conditions' => array(
				'type' => array('Container', 'Page')
			)
		),
		'Eav.Eav' => array(
			'appendToEavModel' => array('layout', 'template')
		),
		'Containable',
		'Util.Default' => array('group_fields' => 'Node.parent_id'),
		'Forest.Leaf'
	);
	var $belongsTo = array(
		'ParentNode' => array(
			'className' => 'Node',
			'foreignKey' => 'parent_id'
		)
	);
	var $hasMany = array(
		'ChildNode' => array(
			'className' => 'Node',
			'foreignKey' => 'parent_id',
			'conditions' => array(
				'ChildNode.visible' => 1
			)
		),
		'NodeAlias'
	);

	/**
	* Modifies app and template fields to the DS system
	* is currently running on - otherwise after a transfer is cant
	* find the files.
	*
	* @param mixed $results
	* @return mixed
	*/
	function afterFind($results) {
		foreach ($results as &$result) {
			if ( !isset($result['Node']) ) {
				continue;
			}
			if ( isset($result['Node']['template']) ) {
				$result['Node']['template'] = str_replace(array('/', '\\'), DS, $result['Node']['template']);
			}
			if ( isset($result['Node']['layout']) ) {
				$result['Node']['layout'] = str_replace(array('/', '\\'), DS, $result['Node']['layout']);
			}
		}
		return $results;
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

	function afterSave()
	{
		// Save URL this and for all children.
		$this->saveField('url', $this->url(), array('validate' => false, 'callbacks' => false));
		$id = $this->id;
		$children = $this->children($this->id);
		foreach ($children as $child) {
			$this->id = $child['Node']['id'];
			$url = $this->url();
			$this->saveField('url', $url, array('validate' => false, 'callbacks' => false));
		}
		$this->id = $id;

		// Clear that cache.
		$this->clearCache();
	}

	function url()
	{
		$path = $this->getPath($this->id);
		$current = end($path);

		// just return the URL if its the URL type
		if ( 'Url' == $current['Node']['type'] ) {
			$url = $current['Node']['url'];
			return $url;
		}

		// skip the menu and url nodes.
		$steps = array();
		foreach ($path as $key => $step) {
			if ( !in_array($step['Node']['type'], array('Menu', 'Url')) ) {
				$steps[] = $step;
			}
		}

		$steps = Set::extract('/Node/slug', $steps);
		$url = '/' . join('/', $steps);
		return $url;
	}

	/**
	* Clears all cache files in the views directory that are a node.
	*/
	function clearCache() {
		$folder = new Folder(TMP . 'cache' . DS . 'views');
		$ls = $folder->ls();
		foreach ($ls[1] as $file)
		{
			$file = new File($folder->pwd() . DS . $file);
			if ( strpos($file->read(), 'baked_simple.Nodes') !== false ) {
				$file->delete();
			}
		}
	}

/**
 * moveUp method
 *
 * After calling the tree behavior method, reset the sequences
 *
 * @param mixed $id
 * @param mixed $steps
 * @param bool $auto
 * @return void
 * @access public
 */
	function moveUp($id = null, $steps = null, $auto = true) {
		if ($this->Behaviors->Tree->moveUp($this, $id, $steps) && $auto) {
			$this->Behaviors->Leaf->resetSequences($this, $this->field('parent_id'));
		}
		return;
	}

/**
 * moveDown method
 *
 * After calling the tree behavior method, reset the sequences
 *
 * @param mixed $id
 * @param mixed $steps
 * @param bool $auto
 * @return void
 * @access public
 */
	function moveDown($id = null, $steps = null, $auto = true) {
		if ($this->Behaviors->Tree->moveDown($this, $id, $steps) && $auto) {
			$this->Behaviors->Leaf->resetSequences($this, $this->field('parent_id'));
		}
		return;
	}
}
?>