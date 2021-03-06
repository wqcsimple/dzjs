<?php
namespace fayfox\models\tables;

use fayfox\core\db\Table;

class PostPropVarchar extends Table{
	protected $_name = 'post_prop_varchar';
	protected $_primary = array('post_id', 'prop_id');
	
	/**
	 * @return PostPropVarchar
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('post_id', 'prop_id'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('content'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'post_id'=>'Post Id',
			'prop_id'=>'Prop Id',
			'content'=>'Content',
		);
	}

	public function filters(){
		return array(
			'post_id'=>'intval',
			'prop_id'=>'intval',
			'content'=>'trim',
		);
	}
}