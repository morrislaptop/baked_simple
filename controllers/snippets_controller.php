<?php
class SnippetsController extends AppController {

	var $name = 'Snippets';
	var $helpers = array('Advindex.Advindex');

	function admin_index() {
		$this->Snippet->recursive = 0;
		$this->set('snippets', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Snippet->create();
			if ($this->Snippet->save($this->data)) {
				$this->_saveRedirect();
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true), 'default', array('class' => 'errorMsg'));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Snippet Content', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Snippet->save($this->data)) {
				$this->_saveRedirect();
			} else {
				$this->Session->setFlash(__('The shared content could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Snippet->read(null, $id);
		}
	}

	function _saveRedirect() {
		$this->Session->setFlash(__('The shared content has been saved', true), 'default', array('class' => 'OKMsg'));
		if ( !empty($this->params['form']['saveList']) ) {
			$this->redirect(array('action'=>'index'));
		}
		else if ( !empty($this->params['form']['saveEdit']) ) {
			$this->redirect(array('action' => 'edit', $this->Snippet->id));
		}
	}
}
?>