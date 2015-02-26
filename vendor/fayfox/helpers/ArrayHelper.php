<?php
namespace fayfox\helpers;

class ArrayHelper{
	/**
	 * php5.5以下版本没有array_column函数<br>
	 * 此方法用于兼容低版本
	 * @param array $array
	 * @param string $column_key
	 * @return array
	 */
	public static function column($array, $column_key){
		if(function_exists('array_column')){
			return array_column($array, $column_key);
		}else{
			$return = array();
			foreach($array as $a){
				$return[] = $a[$column_key];
			}
			return $return;
		}
	}
}