<?php
namespace fayfox\models\tables;

use fayfox\core\db\Table;

class ExamAnswers extends Table{
	protected $_name = 'exam_answers';
	
	/**
	 * @return ExamAnswers
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('id', 'question_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('sort'), 'int', array('min'=>0, 'max'=>255)),
			array(array('is_right_answer'), 'range', array('range'=>array('0', '1'))),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'question_id'=>'Question Id',
			'answer'=>'Answer',
			'is_right_answer'=>'是否正确答案',
			'sort'=>'Sort',
		);
	}

	public function filters(){
		return array(
			'question_id'=>'intval',
			'answer'=>'',
			'is_right_answer'=>'intval',
			'sort'=>'intval',
		);
	}
}