<?php
/* SVN FILE: $Id$ */
/* App schema generated on: 2009-06-05 18:06:36 : 1244189916*/
class AppSchema extends CakeSchema {
	var $name = 'App';

	function before($event = array()) {
		return true;
	}

	function after($event = array()) {
	}


	var $node_aliases = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'node_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'alias' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $nodes = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'parent_id' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'type' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 20),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'menu_title' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'template' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 100),
		'layout' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'url' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'slug' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'enabled' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'visible' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'default' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'lft' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'rght' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'depth' => array('type' => 'integer', 'null' => true, 'default' => NULL),
		'sequence' => array('type' => 'string', 'null' => true, 'default' => NULL, 'length' => 50),
		'first' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'last' => array('type' => 'boolean', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
	var $snippets = array(
		'id' => array('type' => 'integer', 'null' => false, 'default' => NULL, 'key' => 'primary'),
		'title' => array('type' => 'string', 'null' => true, 'default' => NULL),
		'content' => array('type' => 'text', 'null' => true, 'default' => NULL),
		'created' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'modified' => array('type' => 'datetime', 'null' => true, 'default' => NULL),
		'indexes' => array('PRIMARY' => array('column' => 'id', 'unique' => 1))
	);
}
?>