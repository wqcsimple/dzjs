<?php
namespace backend\library;

/**
 * 继承自系统Db类，该类不使用单例模式
 */
class Db extends \fayfox\core\Db{
	public function __construct($config){
		$this->init($config);
	}
}