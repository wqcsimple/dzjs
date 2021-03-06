<?php
namespace fayfox\models\tables;

use fayfox\core\db\Table;

class Logs extends Table{
	/**
	 * 类型 - 正常
	 */
	const TYPE_NORMAL = 0;
	/**
	 * 类型 - 严重错误
	 */
	const TYPE_ERROR = 1;
	/**
	 * 类型 - 警告
	 */
	const TYPE_WARMING = 2;

	protected $_name = 'logs';
	
	/**
	 * @return Logs
	 */
	public static function model($className=__CLASS__){
		return parent::model($className);
	}
	
	public function rules(){
		return array(
			array(array('ip_int'), 'int', array('min'=>-2147483648, 'max'=>2147483647)),
			array(array('id', 'create_time'), 'int', array('min'=>0, 'max'=>4294967295)),
			array(array('user_id'), 'int', array('min'=>0, 'max'=>16777215)),
			array(array('type'), 'int', array('min'=>0, 'max'=>255)),
			array(array('code', 'user_agent'), 'string', array('max'=>255)),
		);
	}

	public function labels(){
		return array(
			'id'=>'Id',
			'user_id'=>'User Id',
			'type'=>'Type',
			'code'=>'Code',
			'data'=>'Data',
			'create_date'=>'Create Date',
			'create_time'=>'Create Time',
			'ip_int'=>'Ip Int',
			'user_agent'=>'User Agent',
		);
	}

	public function filters(){
		return array(
			'user_id'=>'intval',
			'type'=>'intval',
			'code'=>'trim',
			'data'=>'',
			'create_date'=>'',
			'create_time'=>'',
			'ip_int'=>'intval',
			'user_agent'=>'trim',
		);
	}
}