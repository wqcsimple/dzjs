<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;

class GatherController extends AdminController{
	public function getUrl(){
		echo file_get_contents($this->input->get('url'));
	}
}