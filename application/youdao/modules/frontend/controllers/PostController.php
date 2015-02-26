<?php
namespace youdao\modules\frontend\controllers;

use youdao\library\FrontController;
use fayfox\models\Category;
use fayfox\models\tables\Posts;
use fayfox\helpers\String;
use fayfox\core\Sql;
use fayfox\models\tables\Categories;
use fayfox\common\ListView;
use fayfox\core\HttpException;

class PostController extends FrontController{
	public $layout_template = 'inner';
	
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'post';
		$submenu = array(
			array(
				'title'=>'最新资讯',
				'link'=>$this->view->url('post'),
				'class'=>'sel',
			),
		);
		$cats = Category::model()->getNextLevel('_system_post');
		foreach($cats as $c){
			$submenu[] = array(
				'title'=>$c['title'],
				'link'=>$this->view->url('c/'.$c['alias']),
			);
		}
		$this->layout->submenu = $submenu;
	}
	
	public function item(){
		if($this->input->get('alias')){
			$post = Posts::model()->fetchRow(array('alias = ?'=>$this->input->get('alias')));
		}else if($this->input->get('id')){
			$post = Posts::model()->fetchRow(array('id = ?'=>$this->input->get('id', 'intval')));
		}
	
		if(isset($post) && $post){
			$this->view->post = $post;
			//SEO
			$this->layout->title = $post['seo_title'] ? $post['seo_title'] : $post['title'];
			$this->layout->keywords = $post['seo_keywords'] ? $post['seo_keywords'] : $post['title'];
			$this->layout->description = $post['seo_description'] ? $post['seo_description'] : $post['abstract'];
		}else{
			throw new HttpException('页面不存在');
		}
		
		$this->layout->subtitle = '新闻中心';
		$this->layout->breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'最新资讯',
				'link'=>$this->view->url('post'),
			),
			array(
				'title'=>String::niceShort($post['title'], 20, true),
			),
		);
		
		$this->view->render();
	}
	
	public function index(){
		$cat_post = Category::model()->getByAlias('_youdao_post', 'left_value,right_value');
		
		$submenu = array(
			array(
				'title'=>'最新资讯',
				'link'=>$this->view->url('post'),
				'class'=>'sel',
			),
		);
		$cats = Category::model()->getNextLevel('_youdao_post');
		foreach($cats as $c){
			$submenu[] = array(
				'title'=>$c['title'],
				'link'=>$this->view->url('c/'.$c['alias']),
			);
		}
		$this->layout->submenu = $submenu;
		$this->layout->subtitle = '新闻中心';
		$breadcrumbs = array(
			array(
				'title'=>'首页',
				'link'=>$this->view->url(),
			),
			array(
				'title'=>'最新资讯',
				'link'=>$this->view->url('post'),
			),
		);
		
		$sql = new Sql();
		$sql->from('posts', 'p')
			->joinLeft('categories', 'c', 'p.cat_id = c.id')
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
			->where(array(
				'c.left_value > '.$cat_post['left_value'],
				'c.right_value < '.$cat_post['right_value'],
				'p.deleted = 0',
				"p.publish_time < {$this->current_time}",
				'p.status = '.Posts::STATUS_PUBLISH,
			))
		;
		
		if($this->input->get('k')){
			$sql->where(array(
				'p.title like ?'=>'%'.$this->input->get('k').'%',
			));
		}
		
		if($this->input->get('c')){
			$cat = Categories::model()->fetchRow(array('alias = ?'=>$this->input->get('c')));
			if($cat){
				$breadcrumbs[] = array('title'=>$cat['title']);
				//SEO
				$this->layout->title = $cat['seo_title'];
				$this->layout->keywords = $cat['seo_keywords'];
				$this->layout->description = $cat['seo_description'];
			}
			$sql->where(array(
				'c.alias = ?'=>$this->input->get('c'),
			));
		}
		
		$this->view->listview = new ListView($sql, array(
			'pageSize'=>10,
		));
		$this->layout->breadcrumbs = $breadcrumbs;
		$this->layout->banner = 'news-banner.jpg';
		
		$this->view->render();
	}
}