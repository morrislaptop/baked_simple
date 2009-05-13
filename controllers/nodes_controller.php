<?php
class NodesController extends BakedSimpleAppController {

	var $name = 'Nodes';
	var $layout = 'app';
	var $helpers = array('Eav.Eav', 'BakedSimple.Menu', 'BakedSimple.Firecake');
	var $uses = array('BakedSimple.Node', 'BakedSimple.Shared');
	var $components = array('BakedSimple.BakedSimple');

	/**
	* @var Node
	*/
	var $Node;

	function admin_index() {
		$this->Node->recursive = 0;
		$this->Node->order = 'Node.lft';
		$this->set('nodes', $this->paginate());
	}

	function admin_sitemap() {

	}

	function admin_view($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid Node.', true));
			$this->redirect(array('action'=>'index'));
		}
		$this->set('node', $this->Node->read(null, $id));
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Node->create();
			if ($this->Node->save($this->data)) {
				$this->_saveRedirect();
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true), 'default', array('class' => 'errorMsg'));
			}
		}
		$this->_setFormData();
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Node', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			$this->Node->id = $id;
			$save = $this->Node->save($this->data);
			if ( $save ) {
				$this->_saveRedirect();
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true), 'default', array('class' => 'errorMsg'));
			}
		}
		if (empty($this->data)) {
			$conditions = array('Node.id' => $id);
			$eav = true;
			$this->data = $this->Node->find('first', compact('conditions', 'eav'));
		}
		if (empty($this->data['Node']['id']) ) {
			$this->data['Node']['id'] = $id;
		}
		$this->_setAttributes();
		$this->_setFormData();
		$this->render('admin_edit');
	}

	function _setAttributes() {
		$attributes = $this->syncEavAttributes();

		// put template errors in the session.
		if ( $this->templateErrors ) {
			$this->Session->setFlash(implode("\n", $this->templateErrors), 'default', array('class' => 'errorMsg'));
		}
		$this->set(compact('attributes'));
	}

	var $templateFields = array();
	var $templateErrors = array();

	function syncEavAttributes()
	{
		if ( empty($this->data['Node']['template']) || empty($this->data['Node']['layout']) ) {
			$data = $this->Node->find('first', array('fields' => array('template','layout')));
			$template = $data['Node']['template'];
			$layout = $data['Node']['layout'];
		}
		else {
			$template = $this->data['Node']['template'];
			$layout = $this->data['Node']['layout'];
		}
		$template = '/' . str_replace('.ctp', '', $template);
		$layout = array_pop(explode('/', str_replace('.ctp', '', $layout)));

		// include the file, which will be calling field() methods. Since it is being
		// called within this class, then we can automatically create attributes in the
		// eav system (if not already there).
		$templateFields = $this->_mimicRender($template, $layout);

		// bind our model to the attributes table so we can query it.
		$this->Node->bindModel(array('hasMany' => array('EavAttribute')));

		// get what the alias would be based on the template.
		$eavModel = $this->Node->eavModel($template);

		// go through each field called and setup page attributes.
		foreach ($templateFields as $tab => $attributes) {
			foreach ($attributes as $attribute)
			{
				// reset so we don't update previously found attributes.
				$this->Node->EavAttribute->create();

				// check if one exists.
				$conditions = array(
					'name' => $attribute['name'],
					'model' => $eavModel
				);
				$eav_attribute = $this->Node->EavAttribute->find('first', compact('conditions'));
				if ( $eav_attribute ) {
					$this->Node->EavAttribute->id = $eav_attribute['EavAttribute']['id']; // cause an update
				}

				// convert options to php serialize string so we can use it later
				if ( isset($attribute['options']) ) {
					$attribute['options'] = serialize($attribute['options']);
				}

				// save or update, yay!
				$attribute['model'] = $eavModel;
				$this->Node->EavAttribute->save($attribute);
			}
		}

		return $templateFields;
	}

	function _mimicRender($template, $layout = 'ajax') {
		$viewClass = 'BakedSimple.BakedAdmin';
		if ($viewClass != 'View') {
			if (strpos($viewClass, '.') !== false) {
				list($plugin, $viewClass) = explode('.', $viewClass);
			}
			$viewClass = $viewClass . 'View';
			App::import('View', 'BakedSimple.BakedAdmin');
		}
		$View = new $viewClass($this, true);
		$View->render($template, $layout);
		ClassRegistry::removeObject('view');
		return $View->templateFields;
	}

	function _saveRedirect() {
		$this->Session->setFlash(__('The page has been saved', true), 'default', array('class' => 'OKMsg'));
		if ( !empty($this->params['form']['saveList']) ) {
			$this->redirect(array('action'=>'index'));
		}
		else if ( !empty($this->params['form']['saveEdit']) ) {
			$this->redirect(array('action' => 'edit', $this->Node->id));
		}
	}

	function admin_delete($id = null) {
		if (!$id) {
			$this->Session->setFlash(__('Invalid id for Node', true));
			$this->redirect(array('action'=>'index'));
		}
		if ($this->Node->del($id)) {
			$this->Session->setFlash(__('Node deleted', true));
			$this->redirect(array('action'=>'index'));
		}
	}

	function _setFormData()
	{
		$parents = $this->Node->generatetreelist(null, null, null, ' &nbsp; &nbsp; &nbsp; ');
		unset($parents[$this->Node->id]);

		$types = array('Page', 'Container', 'Url', 'Menu');
		$types = array_combine($types, $types);

		$templates = $this->_getTemplates();
		$layouts = $this->_getLayouts();

		$this->set(compact('parents', 'types', 'templates', 'layouts'));
	}

	function _getLayouts()
	{
		App::import(array('Folder', 'File'));
		$folder = new Folder(LAYOUTS);
		$views = $folder->findRecursive('.*\.ctp$');
		$layouts = array();
		foreach ($views as $view) {
			// exclude layouts and elements.
			if ( strpos($view, DS . 'admin') !== false ) {
				continue;
			}

			// turn into english.
			$path = array_pop(explode('/', array_shift(explode('.', str_replace(LAYOUTS, '', $view)))));
			$file = new File($view);
			$layouts[$path] = Inflector::humanize($file->name());
		}
		return $layouts;
	}

	function _getTemplates()
	{
		App::import(array('Folder', 'File'));
		$folder = new Folder(VIEWS);
		$views = $folder->findRecursive('.*\.ctp$');
		$templates = array();
		foreach ($views as $view) {
			// exclude layouts and elements.
			if ( strpos($view, DS . 'layouts'. DS) !== false ||
				 strpos($view, DS . 'elements' . DS) !== false ||
				 strpos($view, DS . Configure::read('Routing.admin') . '_') !== false ) {
				continue;
			}

			// turn into english.
			$path = array_shift(explode('.', str_replace(VIEWS, '', $view)));
			$file = new File($view);
			$templates[$path] = Inflector::humanize($file->name());
		}
		return $templates;
	}

	function admin_nodes()
	{
	    // retrieve the node id that Ext JS posts via ajax
	    $parent = isset($this->params['form']['node']) ? intval($this->params['form']['node']) : null;

	    // find all the nodes underneath the parent node defined above
	    // the second parameter (true) means we only want direct children
	    $nodes = $this->Node->children($parent, true);

	    // send the nodes to our view
	    $this->set(compact('nodes'));
	}

	function admin_reorder()
	{
	    // retrieve the node instructions from javascript
	    // delta is the difference in position (1 = next node, -1 = previous node)
	    $node = intval($this->params['form']['node']);
	    $delta = intval($this->params['form']['delta']);

	    if ($delta > 0) {
	        $this->Node->movedown($node, abs($delta));
	    } elseif ($delta < 0) {
	        $this->Node->moveup($node, abs($delta));
	    }

	    // send success response
	    exit('1');
	}

	function admin_reparent()
	{
	    $node = intval($this->params['form']['node']);
	    $parent = intval($this->params['form']['parent']);
	    $position = intval($this->params['form']['position']);

	    // save the employee node with the new parent id
	    // this will move the employee node to the bottom of the parent list

	    $this->Node->id = $node;
	    $this->Node->saveField('parent_id', $parent);

	    // If position == 0, then we move it straight to the top
	    // otherwise we calculate the distance to move ($delta).
	    // We have to check if $delta > 0 before moving due to a bug
	    // in the tree behavior (https://trac.cakephp.org/ticket/4037)

	    if ($position == 0){
	        $this->Node->moveup($node, true);
	    } else {
	        $count = $this->Node->childcount($parent, true);
	        $delta = $count-$position-1;
	        if ($delta > 0){
	            $this->Node->moveup($node, $delta);
	        }
	    }

	    // send success response
	    exit('1');
	}

	function display() {
		// use internal component to get data
		$template_layout = $this->BakedSimple->pull($this);

		// auto render the template
		$this->render($template_layout['template'], $template_layout['layout']);
	}

	function sitemap() {
		$this->BakedSimple->pull($this);
	}
}
?>