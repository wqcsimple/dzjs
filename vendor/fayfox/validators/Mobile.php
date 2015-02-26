<?php
namespace fayfox\validators;

use fayfox\core\Validator;

class Mobile extends Validator{
	public $pattern = '/^13[0-9]{9}$|^14[0-9]{9}$|^15[0-9]{9}$|^18[0-9]{9}$/';
	
	public $message = '{$attribute}格式不正确';
	
	public function validate($value){
		if(preg_match($this->pattern, $value)){
			return true;
		}else{
			return $this->message;
		}
	}
}