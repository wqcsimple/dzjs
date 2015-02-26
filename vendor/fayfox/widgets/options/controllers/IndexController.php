<?php
namespace fayfox\widgets\options\controllers;

use fayfox\core\Widget;

class IndexController extends Widget{
	
	public function index($options){
		$this->view->data = $options;
		$this->view->render();
	}
}