<?php

App::uses('AppController', 'Controller');

/**
 * Reviews Controller
 *
 * @property Review $Review
 */
class ReviewsController extends AppController {

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
		$this->Review->recursive = 1;
		$this->paginate = array('Review' => array('order' => 'Review.id DESC'));
		$reviews = $this->paginate();
		$this->set(array(
			'reviews' => $reviews,
			'_serialize' => array('reviews')
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
		$this->Review->id = $id;
		if (!$this->Review->exists()) {
			throw new NotFoundException(__('Invalid review'));
		}
		$review  = $this->Review->read(null, $id);
		$this->set(array(
			'review' => $review,
			'_serialize' => array('review')
		));
	}

	/**
	 * add method
	 *
	 * @return void
	 */
	public function add() {
		if ($this->request->is('post')) {
			$this->Review->create();
			if ($this->Review->save($this->request->data)) {
				$message = 'The review has been saved';
				$success = true;
				if (empty($this->request->params['ext'])) {
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$message = 'The review could not be saved. Please, try again.';
				$success = false;
			}
			$this->set(array(
				'success' => $success,
				'message' => $message,
				'_serialize' => array('message', 'success')
			));
		}
		$movies = $this->Review->Movie->find('list');
		$this->set(compact('movies'));
	}

	/**
	 * edit method
	 *
	 * @throws NotFoundException
	 * @param string $id
	 * @return void
	 */
	public function edit($id = null) {
		$this->Review->id = $id;
		$message = '';
		if (!$this->Review->exists()) {
			throw new NotFoundException(__('Invalid review ID'));
		}
		if ($this->request->is('post') || $this->request->is('put')) {
			if ($this->Review->save($this->request->data)) {
				$message = 'The review has been saved';
				$success = true;
				if (empty($this->request->params['ext'])) {
					$this->redirect(array('action' => 'index'));
				}
			} else {
				$message = 'The review could not be saved. Please, try again.';
				$success = false;
			}
		} else {
			$review = $this->request->data = $this->Review->read(null, $id);
		}
		
		$this->set(array(
			'success' => $success,
			'message' => $message,
			'review' => $review,
			'_serialize' => array('message', 'review', 'success')
		));
		$movies = $this->Review->Movie->find('list');
		$this->set(compact('movies'));
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
		$this->Review->id = $id;
		if (!$this->Review->exists()) {
			throw new NotFoundException(__('Invalid review'));
		}
		if ($this->Review->delete()) {
			$this->Session->setFlash(__('Review deleted'));
			$this->redirect(array('action' => 'index'));
		}
		$this->Session->setFlash(__('Review was not deleted'));
		$this->redirect(array('action' => 'index'));
	}

}
