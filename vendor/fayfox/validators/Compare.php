<?php
namespace fayfox\validators;

use fayfox\core\Validator;

class Compare extends Validator{
	public $message;
	
	public $operator = '==';
	
	/**
	 * 跟另一个用户输入参数进行比较
	 */
	public $compare_attribute;
	
	/**
	 * 跟某个特定的值进行比较
	 */
	public $compare_value;
	
	public function validate($value){
		if($this->compare_value !== null){
			$compare_value = $this->compare_value;
		}else if($this->compare_attribute !== null){
			$compare_value = \F::app()->input->request($this->compare_attribute);
		}else{
			$compare_value = \F::app()->input->request($this->_field.'_repeat');
		}
		
		if($this->compareValues($this->operator, $value, $compare_value)){
			return true;
		}else{
			return $this->message;
		}
	}
	
	protected function compareValues($operator, $value, $compare_value)
	{
		switch ($operator) {
			case '==': return $value == $compare_value;
			case '===': return $value === $compare_value;
			case '!=': return $value != $compare_value;
			case '!==': return $value !== $compare_value;
			case '>': return $value > $compare_value;
			case '>=': return $value >= $compare_value;
			case '<': return $value < $compare_value;
			case '<=': return $value <= $compare_value;
			default: return false;
		}
	}
}