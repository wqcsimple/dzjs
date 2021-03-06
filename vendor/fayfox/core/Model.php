<?php
namespace fayfox\core;

use fayfox\core\FBase;

class Model extends FBase{
	/**
	 * @var Session
	 */
	public $session;
	/**
	 * @var Db
	 */
	public $db = null;
	
	private static $_models = array();
	
	public function __construct(){
		$this->session = Session::getInstance();
		$this->db = Db::getInstance();
	}
	
	/**
	 * 获取一个model实例（单例模式）
	 * @param string $class_name
	 */
	public static function model($class_name = __CLASS__){
		if(isset(self::$_models[$class_name])){
			return self::$_models[$class_name];
		}else{
			return self::$_models[$class_name] = new $class_name();
		}
	}
	
	public function rules(){
		return array();
	}
	
	public function labels(){
		return array();
	}
	
	public function filters(){
		return array();
	}
}