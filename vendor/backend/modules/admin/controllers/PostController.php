<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;
use fayfox\models\Category;
use fayfox\models\tables\Categories;
use fayfox\models\tables\Tags;
use fayfox\models\tables\Props;
use fayfox\models\Prop;
use fayfox\models\tables\Posts;
use fayfox\models\tables\PostCategories;
use fayfox\models\Tag;
use fayfox\models\tables\Files;
use fayfox\models\tables\PostFiles;
use fayfox\models\tables\Actionlogs;
use fayfox\models\Setting;
use fayfox\core\Sql;
use fayfox\common\ListView;
use fayfox\models\Post;
use fayfox\core\Response;
use fayfox\helpers\Html;
use fayfox\core\Hook;
use fayfox\core\HttpException;

class PostController extends AdminController{
	public $boxes = array(
		array('name'=>'alias', 'title'=>'别名'),
		array('name'=>'views', 'title'=>'阅读数'),
		array('name'=>'likes', 'title'=>'点赞数'),
		array('name'=>'publish-time', 'title'=>'发布时间'),
		array('name'=>'main-category', 'title'=>'主分类'),
		array('name'=>'category', 'title'=>'附加分类'),
		array('name'=>'thumbnail', 'title'=>'缩略图'),
		array('name'=>'seo', 'title'=>'SEO优化'),
		array('name'=>'abstract', 'title'=>'摘要'),
		array('name'=>'tags', 'title'=>'标签'),
		array('name'=>'files', 'title'=>'附件'),
		array('name'=>'props', 'title'=>'附加属性'),
		array('name'=>'gather', 'title'=>'采集器'),
	);
	
	/**
	 * 默认box排序
	 */
	public $default_box_sort = array(
		'side'=>array(
			'publish-time', 'thumbnail', 'main-category', 'views', 'likes', 'alias', 'props', 'gather',
		),
		'normal'=>array(
			'abstract', 'tags', 'files', 'seo'
		),
	);
	
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'post';
	}
	
	public function create(){
		$cat_id = $this->input->get('cat_id', 'intval');
		$cat = Category::model()->get($cat_id, 'title,left_value,right_value');
		
		if(!$cat){
			throw new HttpException('所选分类不存在');
		}
		
		//hook
		Hook::getInstance()->call('before_post_create', array(
			'cat_id'=>$cat_id,
		));
		
		$cat_parents = Categories::model()->fetchCol('id', array(
			'left_value <= '.$cat['left_value'],
			'right_value >= '.$cat['right_value'],
		));
		$this->layout->subtitle = '撰写文章 - 所属分类：'.$cat['title'];
		
		//查询所有标签
		$this->view->tags = Tags::model()->fetchAll(array(), 'id, title');
		
		//设置附加属性
		$this->view->props = Prop::model()->getAll($cat_parents, Props::TYPE_POST_CAT);
		
		//分类树
		$this->view->cats = Category::model()->getTree('_system_post');
		
		$this->form()->setModel(Posts::model());
		if($this->input->post()){
			if($this->form()->check()){
				//添加posts表
				$data = $this->form()->getFilteredData();
				$data['create_time'] = $this->current_time;
				$data['last_modified_time'] = $this->current_time;
				$data['user_id'] = $this->current_user;
				empty($data['publish_time']) ? $data['publish_time'] = $this->current_time : $data['publish_time'] = strtotime($data['publish_time']);
				$data['publish_date'] = date('Y-m-d', $data['publish_time']);
				isset($data['cat_id']) || $data['cat_id'] = $cat_id;
				$post_id = Posts::model()->insert($data);
				
				//文章分类
				$post_category = $this->form()->getData('post_category');
				if(!empty($post_category)){
					foreach($post_category as $post_cat){
						PostCategories::model()->insert(array(
							'post_id'=>$post_id,
							'cat_id'=>$post_cat,
						));
					}
				}
				//添加到标签表
				if($this->input->post('tags')){
					Tag::model()->set($this->input->post('tags'), $post_id);
				}
				
				//设置附件
				$desc = $this->input->post('description');
				$files = $this->input->post('files', 'intval', array());
				$i = 0;
				foreach($files as $f){
					$i++;
					$file = Files::model()->find($f, 'is_image');
					PostFiles::model()->insert(array(
						'file_id'=>$f,
						'post_id'=>$post_id,
						'description'=>$desc[$f],
						'is_image'=>$file['is_image'],
						'sort'=>$i,
					));
				}
				
				//设置属性
				Prop::model()->createPropertySet('post_id', $post_id, $this->view->props, $this->input->post('props'), array(
					'varchar'=>'fayfox\models\tables\PostPropVarchar',
					'int'=>'fayfox\models\tables\PostPropInt',
					'text'=>'fayfox\models\tables\PostPropText',
				));
				
				//hook
				Hook::getInstance()->call('after_post_created', array(
					'post_id'=>$post_id,
				));
				
				$this->actionlog(Actionlogs::TYPE_POST, '添加文章', $post_id);
				Response::output('success', '文章发布成功', array('admin/post/edit', array(
					'id'=>$post_id,
				)));
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		
		$this->form()->setData(array(
			'cat_id'=>$cat_id,
		));
		
		$_box_sort_settings = Setting::model()->get('admin_post_box_sort');
		$_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
		$this->view->_box_sort_settings = $_box_sort_settings;

		$this->layout->_setting_panel = '_setting_edit';
		$_setting_key = 'admin_post_boxes';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array();
		$this->form('setting')
			->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
				'enabled_boxes'=>$this->getEnabledBoxes($_setting_key),
			));
		
		//所有文章分类
		$this->view->cats = Category::model()->getTree('_system_post');
		
		$this->layout->_help = '_help';
		
		$this->view->render();
	}
	
	public function index(){
		$this->layout->subtitle = '文章';
		$this->layout->_setting_panel = '_setting_index';
		
		$_setting_key = 'admin_post_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('main_category', 'status', 'publish_time', 'last_modified_time', 'create_time', 'sort'),
			'display_name'=>'username',
			'display_time'=>'short',
			'page_size'=>10,
		);
		$this->form('setting')
			->setModel(Setting::model())
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key
			))
			->setJsModel('setting');
		
		$this->view->enabled_boxes = $this->getEnabledBoxes('admin_post_boxes');
		//查找文章分类
		$this->view->cats = Category::model()->getTree('_system_post');
		
		$sql = new Sql();
		$sql->from('posts', 'p', '!content')
			->joinLeft('categories', 'c', 'p.cat_id = c.id', 'title AS cat_title');
		
		if(in_array('user', $_settings['cols'])){
			$sql->joinLeft('users', 'u', 'p.user_id = u.id', 'username,nickname,realname');
		}
		
		//文章状态
		if($this->input->get('deleted', 'intval') == 1){
			$sql->where('p.deleted = 1');
		}else if($this->input->get('status', 'intval') !== null && $this->input->get('delete', 'intval') != 1){
			$sql->where(array(
				'p.status = ?'=>$this->input->get('status', 'intval'),
				'p.deleted <> 1',
			));
		}else{
			$sql->where('p.deleted = 0');
		}
		
		//获得表单提交数据
		if($this->input->get('title')){
			$sql->where(array('p.title LIKE ?'=>'%'.$this->input->get('title').'%'));
		}
		if($this->input->get('start_time')){
			$sql->where(array("p.{$this->input->get('time_field')} > ?"=>$this->input->get('start_time', 'strtotime')));
		}
		if($this->input->get('end_time')){
			$sql->where(array("p.{$this->input->get('time_field')} < ?"=>$this->input->get('end_time', 'strtotime')));
		}
		if($cat_id = $this->input->get('cat_id', 'intval')){
			if($this->input->get('with_child')){
				//包含子分类搜索
				$cats = Category::model()->getAllIdsByParentId($cat_id);
				$cats[] = $cat_id;
				if($this->input->get('with_slave')){
					//包含文章从分类搜索
					$sql->joinLeft('post_categories', 'pc', 'p.id = pc.post_id')
						->orWhere(array(
							'pc.cat_id IN (?)'=>$cats,
							'p.cat_id IN (?)'=>$cats,
						))
						->distinct(true);
				}else{
					//仅根据文章主分类搜索
					$sql->where(array('p.cat_id IN (?)'=>$cats));
				}
			}else{
				if($this->input->get('with_slave')){
					//包含文章从分类搜索
					$sql->joinLeft('post_categories', 'pc', 'p.id = pc.post_id')
						->orWhere(array(
							'pc.cat_id = ?'=>$cat_id,
							'p.cat_id = ?'=>$cat_id,
						))
						->distinct(true);
				}else{
					//仅根据文章主分类搜索
					$sql->where(array('p.cat_id = ?'=>$cat_id));
				}
			}
		}
		
		if($tag_id = $this->input->get('tag_id', 'intval')){
			$sql->joinLeft('posts_tags', 'pt', 'p.id = pt.post_id')
				->where(array(
					'pt.tag_id = ?'=>$tag_id,
				))
				->distinct(true);
		}
		
		$sql->countBy('DISTINCT p.id');
		
		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('p.id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'pageSize'=>$this->form('setting')->getData('page_size', 10),
			'emptyText'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 2).'" align="center">无相关记录！</td></tr>',
		));
		$this->view->render();
	}
	
	public function edit(){
		//可用的box
		$this->layout->_setting_panel = '_setting_edit';
		$_setting_key = 'admin_post_boxes';
		//这里获取enabled_boxes是为了更新文章的时候用
		//由于box可能被hook改掉，后面还会再获取一次enabled_boxes
		$enabled_boxes = $this->getEnabledBoxes($_setting_key);
		
		$post_id = intval($this->input->get('id', 'intval'));
		if(empty($post_id)){
			throw new HttpException('参数不完整', 500);
		}
		
		//所有附加属性
		$post = Posts::model()->find($post_id, 'cat_id');
		if(!$post){
			throw new HttpException('无效的文章ID');
		}
		$cat = Category::model()->get($post['cat_id'], 'title,left_value,right_value');
		
		//若分类已被删除，将文章归为根分类
		if(!$cat){
			$cat = Category::model()->getByAlias('_system_post', 'id,title,left_value,right_value');
			Posts::model()->update(array(
				'cat_id'=>$cat['id'],
			), $post_id);
			$this->flash->set('文章分类异常，请重新设置文章分类', 'attention');
		}
		
		$this->layout->subtitle = '编辑文章- 所属分类：'.$cat['title'];
		$cat_parents = Categories::model()->fetchCol('id', array(
			'left_value <= '.$cat['left_value'],
			'right_value >= '.$cat['right_value'],
		));
		$this->view->props = Prop::model()->getAll($cat_parents, Props::TYPE_POST_CAT);
		
		$this->form()->setModel(Posts::model());
		
		if($this->input->post()){
			if($this->form()->check()){
				$old_post = Posts::model()->find($post_id, 'cat_id');
				//主分类被改了，重新获取分类属性
				if($this->input->post('cat_id') && $old_post['cat_id'] != $this->input->post('cat_id')){
					$cat = Category::model()->get($this->input->post('cat_id', 'intval'), 'title,left_value,right_value');
					$this->layout->subtitle = '编辑文章- 所属分类：'.$cat['title'];
					$cat_parents = Categories::model()->fetchCol('id', array(
						'left_value <= '.$cat['left_value'],
						'right_value >= '.$cat['right_value'],
					));
					$this->view->props = Prop::model()->getAll($cat_parents, Props::TYPE_POST_CAT);
				}
				//更新posts表
				$data = $this->form()->getFilteredData();
				$data['last_modified_time'] = $this->current_time;
				if(in_array('publish_time', $enabled_boxes) && empty($data['publish_time'])){
					$data['publish_time'] = $this->current_time;
					$data['publish_date'] = date('Y-m-d', $data['publish_time']);
					$this->form()->setData(array(
						'publish_time'=>date('Y-m-d H:i:s', $data['publish_time']),
					));
				}else{
					$data['publish_time'] = strtotime($data['publish_time']);
					$data['publish_date'] = date('Y-m-d', $data['publish_time']);
				}
				Posts::model()->update($data, $post_id);
				
				//文章分类
				if(in_array('category', $enabled_boxes)){
					$post_category = $this->form()->getData('post_category');
					if(!empty($post_category)){
						//删除被删除了的分类
						PostCategories::model()->delete(array(
							'post_id = ?'=>$post_id,
							'or'=>array(
								'cat_id NOT IN (?)'=>$post_category,
								'cat_id = ?'=>isset($data['cat_id']) ? $data['cat_id'] : false,//主属性不应出现在附加属性中
							),
						));
						foreach($post_category as $cat_id){
							if(!PostCategories::model()->fetchRow(array(
								'post_id = ?'=>$post_id,
								'cat_id = ?'=>$cat_id,
							))){
								//不存在，则插入
								PostCategories::model()->insert(array(
									'post_id'=>$post_id,
									'cat_id'=>$cat_id,
								));
							}
						}
					}else{
						//用户有权编辑category，但无数据提交，意味着删光了
						//删除全部category
						PostCategories::model()->delete(array(
							'post_id = ?'=>$post_id,
						));
					}
				}
				
				//更新标签
				if(in_array('tags', $enabled_boxes)){
					Tag::model()->set($this->input->post('tags'), $post_id);
				}
				
				//设置files
				if(in_array('files', $enabled_boxes)){
					$desc = $this->input->post('description');
					$files = $this->input->post('files', 'intval', array());
					//删除已被删除的图片
					if($files){
						PostFiles::model()->delete(array(
							'post_id = ?'=>$post_id,
							'file_id NOT IN ('.implode(',', $files).')',
						));
					}else{
						PostFiles::model()->delete(array(
							'post_id = ?'=>$post_id,
						));
					}
					//获取已存在的图片
					$old_files_ids = PostFiles::model()->fetchCol('file_id', array(
						'post_id = ?'=>$post_id,
					));
					$i = 0;
					foreach($files as $f){
						$i++;
						if(in_array($f, $old_files_ids)){
							PostFiles::model()->update(array(
								'description'=>$desc[$f],
								'sort'=>$i,
							), array(
								'post_id = ?'=>$post_id,
								'file_id = ?'=>$f,
							));
						}else{
							$file = Files::model()->find($f, 'is_image');
							PostFiles::model()->insert(array(
								'post_id'=>$post_id,
								'file_id'=>$f,
								'description'=>$desc[$f],
								'sort'=>$i,
								'is_image'=>$file['is_image'],
							));
						}
					}
				}
				
				//附件属性
				if(in_array('props', $enabled_boxes)){
					Prop::model()->updatePropertySet('post_id', $post_id, $this->view->props, $this->input->post('props'), array(
						'varchar'=>'fayfox\models\tables\PostPropVarchar',
						'int'=>'fayfox\models\tables\PostPropInt',
						'text'=>'fayfox\models\tables\PostPropText',
					));
				}

				Hook::getInstance()->call('after_post_created', array(
					'post_id'=>$post_id,
				));
				
				$this->actionlog(Actionlogs::TYPE_POST, '编辑文章', $post_id);
				$this->flash->set('一篇文章被编辑', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		if($post = Posts::model()->find($post_id)){
			//hook
			Hook::getInstance()->call('before_post_update', array(
				'cat_id'=>$post['cat_id'],
				'post_id'=>$post_id,
			));
			
			$post['post_category'] = Post::model()->getCatIds($post_id);
			$post['publish_time'] = date('Y-m-d H:i:s', $post['publish_time']);
			//文章对应标签
			$sql = new Sql();
			$tags = $sql->from('posts_tags', 'pt', '')
				->joinLeft('tags', 't', 'pt.tag_id = t.id', 'title')
				->where('pt.post_id = '.$post_id)
				->fetchAll();
			$tags_arr = array();
			foreach($tags as $t){
				$tags_arr[] = $t['title'];
			}
			$this->form()->setData(array('tags'=>implode(',', $tags_arr)));
			
			//文章对应附加属性值
			$post['props'] = Post::model()->getProps($post_id, $this->view->props);
			
			//分类树
			$this->view->cats = Category::model()->getTree('_system_post');
			
			//post files
			$this->view->files = PostFiles::model()->fetchAll(array(
				'post_id = ?'=>$post_id,
			), 'file_id,description,is_image', 'sort');

			$this->form()->setData($post);
			
			$this->view->post = $post;
			
			$this->layout->sublink = array(
				'uri'=>array('admin/post/create', array(
					'cat_id'=>$post['cat_id'],
				)),
				'text'=>'在此分类下发布文章',
			);
			
			//box排序
			$_box_sort_settings = Setting::model()->get('admin_post_box_sort');
			$_box_sort_settings || $_box_sort_settings = $this->default_box_sort;
			$this->view->_box_sort_settings = $_box_sort_settings;
			
			$enabled_boxes = $this->getEnabledBoxes($_setting_key);
			$_settings = Setting::model()->get($_setting_key);
			$_settings || $_settings = array();
			$this->form('setting')
				->setModel(Setting::model())
				->setJsModel('setting')
				->setData($_settings)
				->setData(array(
					'_key'=>$_setting_key,
					'enabled_boxes'=>$enabled_boxes,
				));
			
			$this->view->render();
		}
	}
	
	public function delete(){
		$id = $this->input->get('id', 'intval');
		Posts::model()->update(array('deleted'=>1), "id = {$id}");
		Tag::model()->refreshCountByPostId($id);
		$this->actionlog(Actionlogs::TYPE_POST, '将文章移入回收站', $id);
		
		Response::output('success', array(
			'message'=>'一篇文章被移入回收站 - '.Html::link('撤销', array('admin/post/undelete', array(
				'id'=>$id,
			))),
			'id'=>$id,
		));
	}
	
	public function undelete(){
		$id = $this->input->get('id', 'intval');
		Posts::model()->update(array('deleted'=>0), array('id = ?'=>$id));
		Tag::model()->refreshCountByPostId($id);
		$this->actionlog(Actionlogs::TYPE_POST, '将文章移出回收站', $id);
		
		Response::output('success', array(
			'message'=>'一篇文章被还原',
			'id'=>$id,
		));
	}
	
	public function remove(){
		$post_id = $this->input->get('id', 'intval');
		
		Post::model()->remove($post_id);
		
		$this->actionlog(Actionlogs::TYPE_POST, '将文章永久删除', $post_id);
		
		Response::output('success', array(
			'message'=>'一篇文章被永久删除',
			'id'=>$post_id,
		));
	}
	
	/**
	 * 文章排序
	 */
	public function sort(){
		$post_id = $this->input->get('id', 'intval');
		$result = Posts::model()->update(array(
			'sort'=>$this->input->get('sort', 'intval'),
		), array(
			'id = ?'=>$post_id,
		));
		$this->actionlog(Actionlogs::TYPE_POST, '改变了文章排序', $post_id);
		
		$post = Posts::model()->find($post_id, 'sort');
		Response::output('success', array(
			'message'=>'一篇文章的排序值被编辑',
			'sort'=>$post['sort'],
		));
	}
	
	/**
	 * 单独渲染一个prop box
	 */
	public function getPropBox(){
		$cat_id = $this->input->get('cat_id', 'intval');
		$post_id = $this->input->get('post_id', 'intval');
		
		$cat = Category::model()->get($cat_id, 'title,left_value,right_value');
		$cat_parents = Categories::model()->fetchCol('id', array(
			'left_value <= '.$cat['left_value'],
			'right_value >= '.$cat['right_value'],
		));
		$this->view->props = Prop::model()->getAll($cat_parents, Props::TYPE_POST_CAT);
		
		//文章对应附加属性值
		if($post_id){
			$this->view->post = array(
				'props'=>Post::model()->getProps($post_id, $this->view->props),
			);
		}else{
			$this->view->post = array('props'=>array());
		}
		
		$this->view->renderPartial('_box_props');
	}
	
	/**
	 * 分类管理
	 */
	public function cat(){
		$this->layout->current_directory = 'post';
	
		$this->layout->subtitle = '文章分类';
		$this->view->cats = Category::model()->getTree('_system_post');
		$root_node = Category::model()->getByAlias('_system_post', 'id');
		$this->view->root = $root_node['id'];
		
		$root_cat = Category::model()->getByAlias('_system_post', 'id');
		if($this->checkPermission('admin/post/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加文章分类',
				'htmlOptions'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'文章',
					'data-id'=>$root_cat['id'],
				),
			);
		}
		
		$this->view->render();
	}
	
	public function batch(){
		$ids = $this->input->post('ids', 'intval');
		$action = $this->input->post('batch_action');
		if(empty($action)){
			$action = $this->input->post('batch_action_2');
		}
		switch($action){
			case 'set-publish':
				if(!$this->checkPermission('admin/post/edit')){
					Response::output('error', array(
						'message'=>'权限不允许',
						'error_code'=>'permission-denied',
					));
				}
				
				$affected_rows = Posts::model()->update(array(
					'status'=>Posts::STATUS_PUBLISH,
				), array(
					'id IN (?)'=>$ids,
				));
				
				//刷新tags的count值
				Tag::model()->refreshCountByPostId($ids);
				
				$this->actionlog(Actionlogs::TYPE_POST, '批处理：'.$affected_rows.'篇文章被发布');
				Response::output('success', $affected_rows.'篇文章被发布');
			break;
			case 'set-draft':
				if(!$this->checkPermission('admin/post/edit')){
					Response::output('error', array(
						'message'=>'权限不允许',
						'error_code'=>'permission-denied',
					));
				}
				
				$affected_rows = Posts::model()->update(array(
					'status'=>Posts::STATUS_DRAFT,
				), array(
					'id IN (?)'=>$ids,
				));
				
				//刷新tags的count值
				Tag::model()->refreshCountByPostId($ids);
				
				$this->actionlog(Actionlogs::TYPE_POST, '批处理：'.$affected_rows.'篇文章被标记为草稿');
				Response::output('success', $affected_rows.'篇文章被标记为草稿');
			break;
			case 'delete':
				if(!$this->checkPermission('admin/post/delete')){
					Response::output('error', array(
						'message'=>'权限不允许',
						'error_code'=>'permission-denied',
					));
				}
				
				$affected_rows = Posts::model()->update(array(
					'deleted'=>1,
				), array(
					'id IN (?)'=>$ids,
				));
				
				//刷新tags的count值
				Tag::model()->refreshCountByPostId($ids);
				
				$this->actionlog(Actionlogs::TYPE_POST, '批处理：'.$affected_rows.'篇文章被移入回收站');
				Response::output('success', $affected_rows.'篇文章被移入回收站');
			break;
			case 'undelete':
				if(!$this->checkPermission('admin/post/undelete')){
					Response::output('error', array(
						'message'=>'权限不允许',
						'error_code'=>'permission-denied',
					));
				}
				
				$affected_rows = Posts::model()->update(array(
					'deleted'=>0,
				), array(
					'id IN (?)'=>$ids,
				));

				//刷新tags的count值
				Tag::model()->refreshCountByPostId($ids);
				
				$this->actionlog(Actionlogs::TYPE_POST, '批处理：'.$affected_rows.'篇文章被还原');
				Response::output('success', $affected_rows.'篇文章被还原');
			break;
			case 'remove':
				if(!$this->checkPermission('admin/post/remove')){
					Response::output('error', array(
						'message'=>'权限不允许',
						'error_code'=>'permission-denied',
					));
				}
				
				foreach($ids as $id){
					Post::model()->remove($id);
				}

				$this->actionlog(Actionlogs::TYPE_POST, '批处理：'.count($ids).'篇文章被永久删除');
				Response::output('success', count($ids).'篇文章被永久删除');
			break;
		}
	}
	
	public function isAliasNotExist(){
		$alias = $this->input->post('value', 'trim');
		if(Posts::model()->fetchRow(array(
			'alias = ?'=>$alias,
			'id != ?'=>$this->input->get('id', 'intval', 0),
		))){
			echo json_encode(array(
				'status'=>0,
				'message'=>'别名已存在',
			));
		}else{
			echo json_encode(array(
				'status'=>1,
			));
		}
	}
}