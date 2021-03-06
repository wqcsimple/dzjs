<?php
namespace fayfox\models;

use fayfox\core\Model;
use fayfox\core\Sql;
use fayfox\helpers\String;
use fayfox\models\tables\AnalystMacs;
use fayfox\models\tables\AnalystVisits;
use fayfox\models\tables\AnalystCaches;

class Analyst extends Model{
	/**
	 * @return Analyst
	 */
	public static function model($className = __CLASS__){
		return parent::model($className);
	}
	
	public function __construct(){
		parent::__construct();
		$this->today = date('Y-m-d');
	}
	
	/**
	 * 以天为单位，统计某一天的新访客，默认为今天
	 * @param date $date
	 * @param int $site
	 */
	public function getNewVisitors($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$macs = AnalystMacs::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'COUNT(*) AS count');
		
		return $macs['count'];
	}
	
	/**
	 * 以天为单位，统计某一天的PV量，默认为今天
	 * @param date $data
	 * @param int $site
	 */
	public function getPV($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$pv = AnalystVisits::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'SUM(views) AS sum');
		
		return empty($pv['sum']) ? 0 : $pv['sum'];
	}
	
	/**
	 * 获取历史访问总量
	 */
	public function getAllPV(){
		$pv = AnalystVisits::model()->fetchRow(array(), 'SUM(views) AS sum');
		
		return empty($pv['sum']) ? 0 : $pv['sum'];
	}

	/**
	 * 以天为单位，统计某一天的UV量，默认为今天
	 * @param date $data
	 * @param int $site
	 */
	public function getUV($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$uv = AnalystVisits::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'COUNT(DISTINCT mac) AS count');
		
		return $uv['count'];
	}

	/**
	 * 以天为单位，统计某一天的独立IP量，默认为今天
	 * @param date $data
	 * @param int $site
	 */
	public function getIP($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$ip = AnalystVisits::model()->fetchRow(array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		), 'COUNT(DISTINCT ip_int) AS count');
		
		return $ip['count'];
	}
	
	public function getBounceRate($date = null, $hour = false, $site = false){
		$date === null && $date = $this->today;
		
		$criteria = array(
			'create_date = ?'=>$date,
			'hour = ?'=>$hour,
			'site = ?'=>$site,
		);
		$where = $this->db->getWhere($criteria);
		
		$sql = "SELECT COUNT(*) AS count FROM (
			SELECT id FROM {$this->db->analyst_visits} WHERE {$where['condition']}
				GROUP BY mac
				HAVING COUNT(*) = 1
			) AS t";
		$result = $this->db->fetchRow($sql, $where['params']);
		
		$uv = $this->getUV($date, $hour, $site);
		if($uv == 0){
			return 0;
		}else{
			return String::money($result['count'] * 100 / $uv);
		}
	}
	
	/**
	 * 缓存非当日的访问数据
	 * @param date $date
	 * @param int $hour
	 * @param int $site
	 */
	public function setCache($date, $hour = false, $site = false){
		if(($date == $this->today && intval($hour) == date('G')) ||
			($date == $this->today && $hour === false)) return false;//当日当时不产生缓存
		
		if($this->getCache($date, $hour, $site)) return false;//不重复生成缓存
		
		$sql = new Sql();
		$result = $sql->from('analyst_visits', 'v', 'SUM(views) AS pv,COUNT(DISTINCT mac) AS uv,COUNT(DISTINCT ip_int) AS ip')
			->where(array(
				'create_date = ?'=>$date,
				'hour = ?'=>$hour,
				'site = ?'=>$site,
			))
			->fetchRow()
		;
		$data = array(
			'date'=>$date,
			'hour'=>$hour === false ? -1 : $hour,
			'site'=>$site ? $site : 0,
			'pv'=>$result['pv'] ? $result['pv'] : 0,
			'uv'=>$result['uv'] ? $result['uv'] : 0,
			'ip'=>$result['ip'] ? $result['ip'] : 0,
			'new_visitors'=>$this->getNewVisitors($date, $hour, $site),
			'bounce_rate'=>$this->getBounceRate($date, $hour, $site),
		);
		AnalystCaches::model()->insert($data);
		return $data;
	}
	
	/**
	 * 获取一条缓存的访问数据
	 * @param date $date
	 * @param int $hour
	 * @param int $site
	 */
	public function getCache($date, $hour = false, $site = false){
		return AnalystCaches::model()->fetchRow(array(
			'date = ?'=>$date,
			'hour = ?'=>$hour === false ? -1 : $hour,
			'site = ?'=>$site ? $site : 0,	//site为0视为全站数据
		));
	}
	
	/**
	 * 获取某一天，以小时为单位的访问数据
	 * @param date $date
	 * @param int $site
	 */
	public function getHourCacheByDay($date, $site = false){
		$result = AnalystCaches::model()->fetchAll(array(
			'date = ?'=>$date,
			'hour != -1',
			'site = ?'=>$site ? $site : 0,	//site为0视为全站数据
		));
		
		$return = array();
		foreach($result as $r){
			$return[$r['hour']] = $r;
		}
		return $return;
	}
}