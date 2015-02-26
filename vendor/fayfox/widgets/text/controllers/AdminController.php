<?php
namespace fayfox\widgets\text\controllers;

use fayfox\core\Widget;

class AdminController extends Widget{
	
	public $title = '文本';
	public $author = 'fayfox';
	public $author_link = 'http://www.fayfox.com';
	public $description = '任意文本或HTML。';
	
	public function index($data){
		$this->view->data = $data;
		$this->view->render();
	}
	
	public function onPost(){
		$this->saveData(array(
			'content'=>$this->input->post('content'),
		));
		$this->flash->set('编辑成功', 'success');
	}
}