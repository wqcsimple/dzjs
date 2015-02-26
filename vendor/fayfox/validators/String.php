<?php
namespace fayfox\validators;

use fayfox\core\Validator;

class String extends Validator{
	/**
	 * 是否允许包含特殊字符
	 * 若为false，仅允许数字，字母，下划线和中横线
	 */
	public $special_characters = true;
	
	public $max;
	
	public $min;
	
	/**
	 * 若设置了equal参数，则min和max参数无效
	 */
	public $equal;
	
	public $too_long = '{$attribute}不能超过{$max}个字符';
	
	public $too_short = '{$attribute}不能少于{$min}个字符';
	
	public $not_equal = '{$attribute}长度必须为{$min}个字符';
	
	public $no_special_characters = '{$attribute}不能包含数字，字母，下划线和中横线以外的特殊字符';
	
	public function validate($value){
		if(!$this->special_characters && !preg_match('/^[a-zA-Z_0-9-]+$/', $value)){
			return $this->no_special_characters;
		}
		
		$len = mb_strlen($value, 'utf-8');
		
		if($this->equal){
			return $len == $this->equal;
		}
		
		if($this->max && $len > $this->max){
			return $this->addError($this->_field, 'string', $this->too_long, array(
				'max'=>$this->max,
			));
		}

		if($this->min && $len < $this->min){
			return $this->addError($this->_field, 'string', $this->too_short, array(
				'min'=>$this->min,
			));
		}
		
		return true;
	}
}