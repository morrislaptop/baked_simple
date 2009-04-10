<?php
class NodesController extends BakedSimpleAppController {

	var $name = 'Nodes';
	var $helpers = array('Html', 'Form', 'Eav.Eav');

	function admin_index() {
		$this->Node->recursive = 0;
		$this->set('nodes', $this->paginate());
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
				$this->Session->setFlash(__('The Node could not be saved. Please, try again.', true));
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
			if ($this->Node->save($this->data)) {
				$this->_saveRedirect();
			} else {
				$this->Session->setFlash(__('The Node could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Node->read(null, $id);
		}
		if (empty($this->data['Node']['id']) ) {
			$this->data['Node']['id'] = $id;
		}
		$this->_setAttributes();
		$this->_setFormData();
	}
	
	function _setAttributes() {
		$attributes = $this->Node->syncEavAttributes();
		$this->set(compact('attributes'));
	}
	
	function _saveRedirect() {
		$this->Session->setFlash(__('The Node has been saved', true));
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
		
		$types = array('Page', 'Container', 'Url');
		$types = array_combine($types, $types);
		
		$templates = $this->_getTemplates();
		
		$this->set(compact('parents', 'types', 'templates'));
	}
	
	function _getTemplates()
	{
		App::import(array('Folder', 'File'));
		$folder = new Folder(VIEWS);
		$views = $folder->findRecursive('.*\.ctp$');
		$templates = array();
		foreach ($views as $view) {
			// exclude layouts and elements.
			if ( strpos($view, DS . 'layouts'. DS) !== false || strpos($view, DS . 'elements' . DS) !== false ) {
				continue;
			}
			
			// turn into english.
			$path = str_replace(VIEWS, '', $view);
			$file = new File($view);
			$templates[$path] = Inflector::humanize($file->name());
		}
		return $templates;
	}

}
?>