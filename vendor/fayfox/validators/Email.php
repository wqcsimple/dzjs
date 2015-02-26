<?php
namespace fayfox\validators;

use fayfox\core\Validator;

class Email extends Validator{
	public $pattern = "/^\\w+([-+.']\\w+)*@\\w+([-.]\\w+)*\\.\\w+([-.]\\w+)*$/";
	
	public $message = '{$attribute}格式不正确';
	
	public function validate($value){
		if(preg_match($this->pattern, $value)){
			return true;
		}else{
			return $this->message;
		}
	}
}