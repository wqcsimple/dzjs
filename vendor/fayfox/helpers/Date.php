<?php
namespace fayfox\helpers;


class Date{
	/**
	 * 返回今天零点的时间戳
	 */
	public static function today(){
		return strtotime('today');
	}
	
	/**
	 * 返回明天零点的时间戳
	 */
	public static function tomorrow(){
		return strtotime('tomorrow');
	}
	
	/**
	 * 返回n天后零点的时间戳
	 * @param int $n 天数
	 */
	public static function daysLater($n){
		return mktime(0, 0, 0, date('m'), date('d') + $n, date('Y'));
	}

	/**
	 * 返回昨天零点的时间戳
	 */
	public static function yesterday(){
		return strtotime('yesterday');
	}
	
	/**
	 * 返回相n天前零点的时间戳
	 * @param int $n 天数
	 */
	public static function daysbefore($n){
		return mktime(0, 0, 0, date('m'), date('d') - $n, date('Y'));
	}
	
	/**
	 * 返回本周第一天的零点和最后一天的23点59分59秒的时间戳
	 */
	public static function thisWeek(){
		$result['first_day'] = mktime(0, 0, 0, date('m'), date('d') - date('N') + 1, date('Y'));
		$result['last_day'] = mktime(23, 59, 59, date('m'), date('d') - date('N') + 7, date('Y'));
		return $result;
	}
	
	/**
	 * 返回本月第一天的零点和最后一天的23点59分59秒的时间戳
	 */
	public static function thisMonth(){
		$result['first_day'] = mktime(0, 0, 0, date('m'), 1, date('y'));
		$result['last_day'] = mktime(23, 59, 59, date('m')+1, 0, date('y'));
		return $result;
	}
	
	/**
	 * 返回指定月第一天的零点和最后一天的23点59分59秒时间戳
	 * 默认为今年
	 * @param int $month
	 * @param int $year
	 */
	public static function month($month, $year = null){
		$year || $year = date('y');
		$result['first_day'] = mktime(0, 0, 0, $month, 1, $year);
		$result['last_day'] = mktime(23, 59, 59, $month+1, 0, $year);
		return $result;
		
	}
	
	/**
	 * 判断是否是今天
	 * @param string $date Unix时间戳
	 */
	public static function isToday($date) {
		return date('Y-m-d', $date) == date('Y-m-d', time());
	}
	
	/**
	 * 判断是否是本月
	 * @param string $date Unix时间戳
	 */
	public static function isThisMonth($date) {
		return date('m Y', $date) == date('m Y', time());
	}
	
	/**
	 * 判断是否是今年
	 * @param string $date Unix时间戳
	 */
	public static function isThisYear($date) {
		return date('Y', $date) == date('Y', time());
	}
	
	/**
	 * 判断是否是昨天
	 * @param string $date Unix时间戳
	 */
	public static function wasYesterday($date) {
		return date('Y-m-d', $date) == date('Y-m-d', strtotime('yesterday'));
	}
	
	public static function isThisWeek($date){
		$week = self::thisWeek();
		return ($date > $week['first_day'] && $date < $week['last_day']);
	}
	
	/**
	 * 根据config文件中设置的时间格式返回时间字符串
	 * @param string $date Unix时间戳
	 */
	public static function format($date){
		if($date != 0){
			static $format;
			if(!empty($format)){
				return date($format, $date);
			}else{
				$config_date = \F::app()->config->get('date');
				$format = $config_date['format'];
				return date($format, $date);
			}
		}else{
			return null;
		}
		
	}
	
	/**
	 * 返回一个简单美化过的时间
	 * @param string $dateString
	 */
	public static function niceShort($date = null) {
		if($date == 0){
			return null;
		}
		
		$dv = \F::app()->current_time - $date;
		if($dv < 0){
			//当前时间之后
			$dv = - $dv;
			if($dv < 60){
				//一分钟内
				return $dv.'秒后';
			}else if($dv < 3600){
				//一小时内
				return floor($dv / 60).'分钟后';
			}else if(self::isToday($date)){
				//今天内
				return floor($dv / 3600).'小时后';
			}else if($dv < (\F::app()->current_time - self::today())+86400*6){
				//7天内
				return ceil(($dv - (\F::app()->current_time - self::today())) / 86400) . '天后';
			}else if(self::isThisYear($date)){
				//今年
				return date('n月j日', $date);
			}else{
				return date('y年n月j日', $date);
			}
		}else{
			//当前时间之前
			if($dv < 3){
				return '刚刚';
			}else if($dv < 60){
				//一分钟内
				return $dv.'秒前';
			}else if($dv < 3600){
				//一小时内
				return floor($dv / 60).'分钟前';
			}else if(self::isToday($date)){
				//今天内
				return floor($dv / 3600).'小时前';
			}else if($dv < (\F::app()->current_time - self::today())+86400*6){
				//7天内
				return ceil(($dv - (\F::app()->current_time - self::today())) / 86400) . '天前';
			}else if(self::isThisYear($date)){
				//今年
				return date('n月j日', $date);
			}else{
				return date('y年n月j日', $date);
			}
		}
	}
	
	/**
	 * 返回两个时间戳之间的时差
	 */
	public static function diff($start_time, $end_time){
		$dv = $end_time - $start_time;
		
		if($dv < 60){
			return $dv.'秒';
		}else if($dv < 3600){
			return floor($dv / 60).'分'.($dv % 60).'秒';
		}else if($dv < 86400){
			$remainder = $dv % 3600;
			return floor($dv / 3600).'小时'.floor($remainder / 60).'分'.($remainder % 60).'秒';
		}else{
			$date_remainder = $dv % 86400;
			$minute_remainder = $date_remainder % 3600;
			return floor($dv / 86400).'天'.floor($date_remainder / 3600).'小时'.floor($minute_remainder / 60).'分'.($minute_remainder % 60).'秒';
		}
	}
	
	/**
	 * 当输入为空字符串时，返回空字符串，其它返回时间戳
	 * @param string $date
	 * @return empty_string|number
	 */
	public static function strtotime($date){
		if($date === ''){
			return '';
		}else{
			return strtotime($date);
		}
	}
}