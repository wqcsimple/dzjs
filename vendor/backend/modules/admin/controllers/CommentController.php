<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;
use fayfox\core\Sql;
use fayfox\models\tables\Messages;
use fayfox\common\ListView;

class CommentController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'message';
	}
	
	public function index(){
		$this->layout->subtitle = '文章评论';
		
		$sql = new Sql();
		$sql->from('messages', 'm')
			->joinLeft('posts', 'p', 'm.target = p.id', 'title AS post_title, id AS post_id')
			->joinLeft('users', 'u', 'm.user_id = u.id', 'realname,username')
			->where('m.type = '.Messages::TYPE_POST_COMMENT)
			->order('id DESC')
		;
		
		if($this->input->get('deleted')){
			$sql->where(array(
				'm.deleted = 1',
			));
		}else if($this->input->get('status') !== null && $this->input->get('status') !== ''){
			$sql->where(array(
				'm.status = ?'=>$this->input->get('status', 'intval'),
				'm.deleted = 0',
			));
		}else{
			$sql->where('m.deleted = 0');
		}
		
		$listview = new ListView($sql, array(
			'pageSize'=>30,
		));
		$this->view->listview = $listview;			
		
		$this->view->render();
	}
}