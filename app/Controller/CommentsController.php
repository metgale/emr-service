<?php
App::uses('AppController', 'Controller');
/**
 * Comments Controller
 *
 * @property Comment $Comment
 */
class CommentsController extends AppController {

	public function beforeFilter() {
		parent::beforeFilter();
		$this->Auth->allow(array('index', 'view'));
	}

/**
 * index method
 *
 * @return void
 */
	public function index() {
		$this->Comment->recursive = 1;
		$comments =  $this->paginate();
		$this->set(array(
			'comments' => $comments,
			'_serialize' => array('comments')
		));
	}

/**
 * view method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function view($id = null) {
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
				
		$comment  = $this->Comment->read(null, $id);
		$this->set(array(
			'comment' => $comment,
			'_serialize' => array('comment')
		));
	}

/**
 * add method
 *
 * @return void
 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Comment->create();
			if ($this->Comment->save($this->request->data)) {
				$message = 'The comment has been saved';
				$success = true;
				if (empty($this->request->params['ext'])) {
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$message = 'The comment could not be saved. Please, try again.';
				$success = false;
			}
			$this->set(array(
				'success' => $success,
				'message' => $message,
				'_serialize' => array('message', 'success')
			));
		}
		$reviews = $this->Comment->Review->find('list');
		$this->set(compact('reviews'));
	}

/**
 * edit method
 *
 * @throws NotFoundException
 * @param string $id
 * @return void
 */
	public function edit($id = null) {
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Comment->save($this->request->data)) {
				$this->Session->setFlash(__('The comment has been saved'));
				$this->redirect(array('action' => 'index'));
			} else {
				$this->Session->setFlash(__('The comment could not be saved. Please, try again.'));
			}
		} else {
			$this->request->data = $this->Comment->read(null, $id);
		}
		$reviews = $this->Comment->Review->find('list');
		$this->set(compact('reviews'));
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
		$this->Comment->id = $id;
		if (!$this->Comment->exists()) {
			throw new NotFoundException(__('Invalid comment'));
		}
		if ($this->Comment->delete()) {
			$this->Session->setFlash(__('Comment deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Comment was not deleted'));
		$this->redirect(array('action' => 'index'));
	}
}
