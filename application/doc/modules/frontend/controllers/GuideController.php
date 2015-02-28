<?php
namespace doc\modules\frontend\controllers;

use doc\library\FrontController;
use fayfox\models\Category;
use fayfox\core\HttpException;
use fayfox\models\Post;

class GuideController extends FrontController{
	public function index(){
		$cat = $this->input->get('cat', 'trim');
		if(is_numeric($cat)){
			$cat = Category::model()->get($cat);
		}else{
			$cat = Category::model()->getByAlias($cat);
		}
		
		if(empty($cat)){
			throw new HttpException('页面不存在');
		}
		
		$this->layout->page_title = $cat['description'] ? "{$cat['title']}（{$cat['description']}）" : $cat['title'];
		$this->layout->title = $cat['title'].' - Fayfox开发文档  - 1.0';

		$breadcrumb = array();
		$parent_path = Category::model()->getParentPath($cat, 'fayfox');
		if($parent_path){
			foreach($parent_path as $p){
				$breadcrumb[] = array(
					'text'=>$p['title'],
					'href'=>$this->view->url($p['alias']),
				);
			}
		}
		$this->layout->breadcrumb = $breadcrumb;
		
		if($cat['right_value'] - $cat['left_value'] == 1){
			//叶子节点
			$this->view->assign(array(
				'cat'=>$cat,
				'posts'=>Post::model()->getByCat($cat, 0, 'id,title,content,content_type', false, 'is_top DESC, sort, publish_time ASC'),
			))->render('posts');
		}else{
			//非叶子
			$this->view->assign(array(
				'cat'=>$cat,
				'cats'=>Category::model()->getNextLevelByParentId($cat['id']),
				'posts'=>Post::model()->getByCat($cat, 0, 'id,title,content,content_type', false, 'is_top DESC, sort, publish_time ASC'),
			))->render('cats');
		}
	}
}