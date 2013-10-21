<?php

App::uses('AppController', 'Controller');

/**
 * Users Controller
 *
 * @property User $User
 */
class UsersController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('add', 'login')); // Letting users register themselves
	}

	public function login() {
		if ($this->Auth->login()) {
			$message = 'Ok';
			$success = true;
			$this->User->id = $this->Auth->user('id');
			if (!$this->User->exists()) {
				throw new NotFoundException(__('Invalid user'));
			}
			$user = $this->User->read(null, $this->User->id);
			//return $this->redirect($this->Auth->redirect());
		} else {
			$message = 'Username or password is incorrect';
			$success = true;
			$user = null;
			$this->Session->setFlash(__('Username or password is incorrect'), 'default', array(), 'auth');
		}
		$this->set(array(
			'user' => $user,
			'success' => $success,
			'message' => $message,
			'_serialize' => array('user', 'message', 'success')
		));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is('post')) {
			$this->User->create();
			if ($this->User->save($this->request->data)) {
				$message = 'The User has been saved';
				$success = true;
				if (!$this->request->params['ext']) {
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$message = 'The User could not be saved. Please, try again.';
				$success = false;
			}
			$this->set(array(
				'success' => $success,
				'message' => $message,
				'_serialize' => array('message', 'success')
			));
		}
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->User->save($this->request->data)) {
				$this->Session->setFlash(__('The user has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The user could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->User->read(null, $id);
		}
	}

	/**
	 * delete method
	 *
	 * @throws MethodNotAllowedException
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function delete($id = null) {
		if (!$this->request->is('post')) {
			throw new MethodNotAllowedException();
		}
		$this->User->id = $id;
		if (!$this->User->exists()) {
			throw new NotFoundException(__('Invalid user'));
		}
		if ($this->User->delete()) {
			$this->Session->setFlash(__('User deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('User was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

}
