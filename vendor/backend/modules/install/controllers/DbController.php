<?php
namespace backend\modules\install\controllers;

use backend\library\InstallController;
use fayfox\models\Category;
use fayfox\core\Db;

class DbController extends InstallController{
	public function __construct(){
		parent::__construct();
		$this->db = Db::getInstance();
	}
	
	public function createTables(){
		$prefix = $this->config->get('db.table_prefix');
		$sql = file_get_contents(__DIR__.'/../data/tables.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
		
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	public function setCities(){
		$prefix = $this->config->get('db.table_prefix');
		$sql = file_get_contents(__DIR__.'/../data/cities.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
		
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	public function setRegions(){
		$prefix = $this->config->get('db.table_prefix');
		$sql = file_get_contents(__DIR__.'/../data/regions.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
		
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	public function setCats(){
		$prefix = $this->config->get('db.table_prefix');
		$sql = file_get_contents(__DIR__.'/../data/cats.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
		
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	public function setActions(){
		$prefix = $this->config->get('db.table_prefix');
		$sql = file_get_contents(__DIR__.'/../data/actions.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
		
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	public function setSystem(){
		$prefix = $this->config->get('db.table_prefix');
		$sql = file_get_contents(__DIR__.'/../data/system.sql');
		$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
		$this->db->execute($sql);
		
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	/**
	 * 安装用户自定义数据
	 */
	public function setCustom(){
		if(file_exists(APPLICATION_PATH . 'data/custom.sql')){
			$prefix = $this->config->get('db.table_prefix');
			if($sql = file_get_contents(APPLICATION_PATH . 'data/custom.sql')){
				$sql = str_replace(array('{{$prefix}}', '{{$time}}'), array($prefix, $this->current_time), $sql);
				$this->db->execute($sql);
			}
		}
		echo json_encode(array(
			'status'=>1,
		));
	}
	
	/**
	 * 对categories表进行索引
	 */
	public function indexCats(){
		Category::model()->buildIndex();
		echo json_encode(array(
			'status'=>1,
		));
	}
}