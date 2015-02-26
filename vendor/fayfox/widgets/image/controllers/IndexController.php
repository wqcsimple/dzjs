<?php
namespace fayfox\widgets\image\controllers;

use fayfox\core\Widget;

class IndexController extends Widget{
	
	public function index($data){
		$this->view->data = $data;
		$this->view->render();
	}
}