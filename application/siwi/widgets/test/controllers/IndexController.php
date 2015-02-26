<?php
namespace siwi\widgets\test\controllers;

use fayfox\core\Widget;

class IndexController extends Widget{
	public function index($options){
		$this->view->render();
	}
}