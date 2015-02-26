<?php
namespace fayfox\validators;

use fayfox\core\Validator;

class Float extends Validator{
	/**
	 * 长度
	 */
	public $length;
	
	/**
	 * 小数位
	 */
	public $decimal = 2;
	
	public $too_long = '{$attribute}必须是小于{$max}的数字';
	
	public $decimal_too_long = '{$attribute}小数位不能多于{$decimal}位';
	
	public $message = '{$attribute}必须是数字';
	
	public function validate($value){
		if(!preg_match('/^\d+(\.\d+)?$/', $value)){
			return $this->addError($this->_field, 'float', $this->message);
		}
		
		$point_pos = strpos($value, '.');
		if($point_pos && strlen($value) - $point_pos - 1 > $this->decimal){
			return $this->addError($this->_field, 'float', $this->decimal_too_long, array(
				'decimal'=>$this->decimal,
			));
		}
		
		
		if($this->length){
			$max = '1'.str_repeat('0', $this->length - $this->decimal);
			if($value > $max){
				return $this->addError($this->_field, 'float', $this->too_long, array(
					'max'=>$max,
					'decimal'=>$this->decimal,
				));
			}
		}
		
		return true;
	}
}