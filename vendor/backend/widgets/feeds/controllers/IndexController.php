<?php
namespace backend\widgets\feeds\controllers;

use fayfox\core\Widget;
use fayfox\models\tables\Logs;

class IndexController extends Widget{
	
	public function index($options){
		$this->view->logs = Logs::model()->fetchAll(array(
			'or'=>array(
				'type = '.Logs::TYPE_ERROR,
				'type = '.Logs::TYPE_WARMING,
			)
		), 'id,code,create_time,type', 'id DESC', 20);
		
		$this->view->render();
	}
	
	public function placeholder(){
		
		$this->view->render('placeholder');
	}
}