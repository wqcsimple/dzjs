<?php
namespace youda\modules\frontend\controllers;

use youda\library\FrontController;
use fayfox\models\Option;
use fayfox\models\tables\Pages;
use fayfox\models\Category;
use fayfox\core\Sql;
use fayfox\models\tables\Posts;

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
		
		//关于我们
		$this->view->about = Pages::model()->fetchRow(array('alias = ?'=>'about'), 'abstract');
		
		//资讯
		$cat_post = Category::model()->getByAlias('_youdao_post', 'left_value,right_value');
		$sql = new Sql();
		$this->view->last_news = $sql->from('posts', 'p', 'id,title,publish_time,abstract,cat_id')
			->joinLeft('categories', 'c', 'p.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'c.left_value > '.$cat_post['left_value'],
				'c.right_value < '.$cat_post['right_value'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISH,
				'p.publish_time < '.$this->current_time,
			))
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
			->limit(5)
			->fetchAll();
			
		$this->view->render();
	}
	
}









