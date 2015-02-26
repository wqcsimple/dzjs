<?php
namespace doc\modules\frontend\controllers;

use doc\library\FrontController;
use fayfox\core\Sql;

class IndexController extends FrontController{
	public function index(){
		$this->layout->title = 'Fayfox开发文档  - 1.0';
		$this->layout->page_title = 'Fayfox开发文档';
		
		$sql = new Sql();
		$sql->from('posts', 'p', 'cat_id')
			->joinLeft('categories', 'c', 'p.cat_id = c.id', 'alias,title')
			->order('last_modified_time DESC')
			->limit(10)
			->group('p.cat_id')
		;
		$this->view->last_modified_cats = $sql->fetchAll();
		
		$this->view->render();
	}
	
}