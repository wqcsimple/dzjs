<?php
namespace blog\widgets\rand_posts\controllers;

use fayfox\core\Widget;
use fayfox\models\tables\Posts;
use fayfox\core\Sql;

class IndexController extends Widget{
	
	public function index($options){
		$sql = new Sql();
		$this->view->posts = $sql->from('posts', 'p', 'id,title,publish_time,comments')
			->where(array(
				'deleted = 0',
				"publish_time < {$this->current_time}",
				'status = '.Posts::STATUS_PUBLISH,
			))
			->order('RAND()')
			->limit(5)
			->fetchAll();
		
		$this->view->render();
	}
}