<?php
class BakedSimpleComponent extends Object {
	//called before Controller::beforeFilter()
	function initialize(&$controller, $settings = array()) {
		// saving the controller reference for later use
		$this->controller =& $controller;
	}

	//called after Controller::beforeFilter()
	function startup(&$controller) {
	}

	//called after Controller::beforeRender()
	function beforeRender(&$controller) {

	}

	//called after Controller::render()
	function shutdown(&$controller) {
	}

	//called before Controller::redirect()
	function beforeRedirect(&$controller, $url, $status=null, $exit=true) {
	}

	/**
	* Called when the controller wants to fetch the data from the CMS for the template (not called automatically on purpose
	*/
	function pull(&$controller, $url = null) {
		// get node
		$Node = ClassRegistry::init('BakedSimple.Node');
		$Shared = ClassRegistry::init('BakedSimple.Shared');

		// get page
		if ( !$url ) {
			$url = '/' . $controller->params['url']['url'];
			$url = str_replace('//', '/', $url);
		}
		$conditions = array('Node.url' => $url);
		$eav = true;
		$contain = array('ParentNode', 'ChildNode');
		$fields = array('Node.*', 'ParentNode.*');
		$node = $Node->find('first', compact('conditions', 'eav', 'contain', 'fields'));

		// try getting URL by alias.
		if ( !$node ) {
			$conditions = array(
				'\'' . $url . '\' REGEXP `alias`'
			);
			$node_alias = $Node->NodeAlias->find('first', compact('conditions'));
			if ( $node_alias ) {
				$conditions = array(
					'Node.id' => $node_alias['NodeAlias']['node_id']
				);
				$node = $Node->find('first', compact('conditions', 'eav', 'contain', 'fields'));
			}
		}

		// catch a missing page here.
		if ( !$node ) {
			$conditions = array(
				'Node.id' => Configure::read('BakedSimple.missing_node_id')
			);
			$node = $Node->find('first', compact('conditions', 'eav', 'contain', 'fields'));
		}

		// get template and layout so we can return them from this function
		$template = DS . $node['Node']['template'];
		$layout = $node['Node']['layout'];

		// get menu
		$contain = array();
		$order = 'lft ASC';
		$conditions = array(
			'visible' => 1
		);
		$fields = array('Node.id', 'Node.title', 'Node.menu_title', 'Node.parent_id', 'Node.url', 'Node.slug');
		$nodes  = $Node->find('threaded', compact('contain', 'order', 'conditions', 'fields'));

		// get global content
		$shareds = $Shared->find('all');

		// get siblings for the wicked as menus.
		$conditions = array(
			'Node.parent_id' => $node['Node']['parent_id']
		);
		$contain = array();
		$order = 'lft ASC';
		$siblings = $Node->find('all', compact('conditions', 'contain', 'order', 'fields'));

		// run
		$vars = compact('nodes', 'node', 'shareds', 'siblings', 'template', 'layout');
		$controller->set($vars);
		return $vars;
	}
}
?>