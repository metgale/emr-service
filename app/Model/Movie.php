<?php
App::uses('AppModel', 'Model');
/**
 * Movie Model
 *
 * @property Review $Review
 */
class Movie extends AppModel {

/**
 * Validation rules
 *
 * @var array
 */
	public $validate = array(
		'title' => array(
			'notempty' => array(
				'rule' => array('notempty'),
				//'message' => 'Your custom message here',
				//'allowEmpty' => false,
				//'required' => false,
				//'last' => false, // Stop validation after this rule
				//'on' => 'create', // Limit validation to 'create' or 'update' operations
			),
		),
	);

	//The Associations below have been created with all possible keys, those that are not needed can be removed

/**
 * hasMany associations
 *
 * @var array
 */
	public $hasMany = array(
		'Review' => array(
			'className' => 'Review',
			'foreignKey' => 'movie_id',
			'dependent' => false,
			'conditions' => '',
			'fields' => '',
			'order' => 'Review.id DESC', 
			'limit' => '',
			'offset' => '',
			'exclusive' => '',
			'finderQuery' => '',
			'counterQuery' => ''
		)
	);

}
