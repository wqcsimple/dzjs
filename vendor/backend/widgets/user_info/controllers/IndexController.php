<?php
namespace backend\widgets\user_info\controllers;

use fayfox\core\Widget;
use fayfox\core\Loader;

class IndexController extends Widget{
	
	public function index($options){
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render();
	}
	
	public function placeholder(){
		
		$this->view->render('placeholder');
	}
}