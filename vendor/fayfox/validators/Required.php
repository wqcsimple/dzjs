<?php
namespace fayfox\validators;

use fayfox\core\Validator;

class Required extends Validator{
	public $message = '{$attribute}是必填字段';
	
	public $skip_on_empty = false;
	
	/**
	 * 是否允许空字符串
	 */
	public $enableEmpty = false;
	
	public function validate($value){
		if($this->enableEmpty){
			//只要有提交，即便是空字符串，也通过验证
			if($value === null){
				return $this->message;
			}else{
				return true;
			}
		}else{
			if(empty($value)){
				return $this->message;
			}else{
				return true;
			}
		}
	}
}