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

		// Save this URL
		if ( isset($this->data['Node']['type']) ) {
			if ( !in_array($this->data['Node']['type'], array('Url', 'Menu')) ) {
				$this->saveField('url', $this->url(), array('validate' => false, 'callbacks' => false));
			}
			if ( 'Menu' == $this->data['Node']['type'] ) {
				$this->saveField('url', null, array('validate' => false, 'callbacks' => false));
			}
		}

		// Save URL for all children.
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

		// skip the menu nodes.
		$steps = array();
		foreach ($path as $key => $step) {
			if ( 'Menu' != $step['Node']['type'] ) {
				$steps[] = $step;
			}
		}

		$steps = Set::extract('/Node/slug', $steps);
		$me = array_pop($path);
		if ( false && $me['Node']['default'] ) {
			array_pop($steps);
		}

		$url = '/' . join('/', $steps);
		return $url;
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