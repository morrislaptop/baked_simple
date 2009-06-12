<?php
class SharedsController extends AppController {

	var $name = 'Shareds';

	function admin_index() {
		$this->Shared->recursive = 0;
		$this->set('shareds', $this->paginate());
	}

	function admin_add() {
		if (!empty($this->data)) {
			$this->Shared->create();
			if ($this->Shared->save($this->data)) {
				$this->_saveRedirect();
			} else {
				$this->Session->setFlash(__('The page could not be saved. Please, try again.', true), 'default', array('class' => 'errorMsg'));
			}
		}
	}

	function admin_edit($id = null) {
		if (!$id && empty($this->data)) {
			$this->Session->setFlash(__('Invalid Shared Content', true));
			$this->redirect(array('action'=>'index'));
		}
		if (!empty($this->data)) {
			if ($this->Shared->save($this->data)) {
				$this->_saveRedirect();
			} else {
				$this->Session->setFlash(__('The shared content could not be saved. Please, try again.', true));
			}
		}
		if (empty($this->data)) {
			$this->data = $this->Shared->read(null, $id);
		}
	}

	function _saveRedirect() {
		$this->Session->setFlash(__('The shared content has been saved', true), 'default', array('class' => 'OKMsg'));
		if ( !empty($this->params['form']['saveList']) ) {
			$this->redirect(array('action'=>'index'));
		}
		else if ( !empty($this->params['form']['saveEdit']) ) {
			$this->redirect(array('action' => 'edit', $this->Shared->id));
		}
	}
}
?>