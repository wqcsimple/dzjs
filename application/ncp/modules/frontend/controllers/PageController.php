<?php
namespace ncp\modules\frontend\controllers;

use ncp\library\FrontController;
use fayfox\models\tables\Pages;
use fayfox\core\HttpException;

class PageController extends FrontController{
	public function __construct(){
		parent::__construct();
	}
	
	public function item(){
		if($this->input->get('alias')){
			$page = Pages::model()->fetchRow(array('alias = ?'=>$this->input->get('alias')));
		}else if($this->input->get('id')){
			$page = Pages::model()->fetchRow(array('id = ?'=>$this->input->get('id', 'intval')));
		}
		
		if(isset($page) && $page){
			Pages::model()->inc($page['id'], 'views', 1);
			$this->view->page = $page;
		}else{
			throw new HttpException('您请求的页面不存在');
		}

		$this->view->render();
	}
}