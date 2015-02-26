<?php
namespace backend\widgets\js_info\controllers;

use fayfox\core\Widget;

class IndexController extends Widget{
	public function index($options){
		$this->view->render();
	}
}