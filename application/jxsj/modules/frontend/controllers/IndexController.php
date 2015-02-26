<?php
namespace jxsj\modules\frontend\controllers;

use jxsj\library\FrontController;
use fayfox\models\Option;
use fayfox\models\Page;

class IndexController extends FrontController{
	public function __construct(){
		parent::__construct();
		
		$this->layout->title = '';
		$this->layout->keywords = '';
		$this->layout->description = '';
	}
	
	public function index(){
		$this->layout->keywords = Option::get('seo_index_keywords');
		$this->layout->description = Option::get('seo_index_description');
		
		$this->view->about = Page::model()->getByAlias('about');
		
		$this->view->render();
	}
	
}