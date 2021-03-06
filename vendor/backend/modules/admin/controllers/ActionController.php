<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;
use fayfox\models\Category;
use fayfox\models\tables\Actions;
use fayfox\models\tables\Actionlogs;
use fayfox\core\Sql;
use fayfox\common\ListView;
use fayfox\core\Response;

class ActionController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'role';
	}
	
	public function index(){
		$this->layout->subtitle = '添加权限';
		$this->flash->set('如果您不清楚它的是干嘛用的，请不要随意修改，后果可能很严重！', 'attention');
		
		$this->_setListview();

		$this->view->cats = Category::model()->getTree('_system_action', 'id,title');
		$this->form()->setModel(Actions::model())
			->addRule(array('parent_router', 'ajax', array('url'=>array('admin/action/is-router-exist'))))
			->setLabels(array('parent_router'=>'父级路由'))
		;
		$this->view->render();
	}
	
	public function create(){
		if($this->input->post()){
			if($this->form()->setModel(Actions::model())
				->addRule(array(array('parent_router',), 'exist', array('table'=>'actions', 'field'=>'router')))
				->setLabels(array('parent_router'=>'父级路由'))
				->check()){
				if($this->input->post('parent_router')){
					$parent_router = Actions::model()->fetchRow(array(
						'router = ?'=>$this->input->post('parent_router', 'trim'),
					), 'id');
					if(!$parent_router){
						$this->flash->set('父级路由不存在');
						Response::output('error', '父级路由不存在');
					}
					$parent = $parent_router['id'];
				}else{
					$parent = 0;
				}
				$data = $this->form()->getFilteredData();
				$data['parent'] = $parent;
				$result = Actions::model()->insert($data);
				$this->actionlog(Actionlogs::TYPE_ACTION, '添加权限', $result);
				Response::output('success', '权限添加成功');
			}else{
				Response::output('error', $this->showDataCheckError($this->form()->getErrors(), true));
			}
		}else{
			Response::output('error', '不完整的请求');
		}
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑权限';
		$gets = $this->input->get();
		unset($gets['id']);
		$this->layout->sublink = array(
			'uri'=>array('admin/action/index', $gets),
			'text'=>'添加权限',
		);
		$action_id = intval($this->input->get('id', 'intval'));
		$this->view->cats = Category::model()->getNextLevel('_system_action');
		
		$this->form()->setModel(Actions::model())
			->addRule(array(array('parent_router',), 'exist', array('table'=>'actions', 'field'=>'router', 'ajax'=>array('admin/action/is-router-exist'))))
			->setLabels(array('parent_router'=>'父级路由'));
		
		if($this->input->post()){
			if($this->form()->check()){
				if($this->input->post('parent_router')){
					$parent_router = Actions::model()->fetchRow(array(
						'router = ?'=>$this->input->post('parent_router'),
					), 'id');
					if(!$parent_router){
						die('父级路由不存在');
					}
					$parent = $parent_router['id'];
				}else{
					$parent = 0;
				}
				$data = $this->form()->getFilteredData();
				$data['parent'] = $parent;
				isset($data['is_public']) || $data['is_public'] = 0;
				Actions::model()->update($data, "id = {$action_id}");
				$this->actionlog(Actionlogs::TYPE_ACTION, '编辑管理员权限', $action_id);
				$this->flash->set('权限编辑成功', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}

		$action = Actions::model()->find($action_id);
		if($action['parent']){
			$parent_action = Actions::model()->find($action['parent'], 'router');
			$action['parent_router'] = $parent_action['router'];
		}
		$this->form()->setData($action);
		
		$this->_setListview();
		$this->view->render();
	}
	
	public function remove(){
		Actions::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
		$this->actionlog(Actionlogs::TYPE_ACTION, '删除权限', $this->input->get('id', 'intval'));
		
		Response::output('success', '一个权限被删除', $this->view->url('admin/action/index', $this->input->get()));
	}
	
	public function search(){
		$actions = Actions::model()->fetchAll(array(
			'router LIKE ?'=>'%'.$this->input->get('key', false).'%'
		), 'id,router AS title', 'title', 20);
		echo json_encode(array(
			'status'=>1,
			'data'=>$actions,
		));
	}
	
	public function isRouterNotExist(){
		if(Actions::model()->fetchRow(array(
			'router = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false),
		))){
			echo json_encode(array('status'=>0, 'message'=>'该路由已存在'));
		}else{
			echo json_encode(array('status'=>1));
		}
	}
	
	public function isRouterExist(){
		if(Actions::model()->fetchRow(array(
			'router = ?'=>$this->input->post('value', 'trim'),
		))){
			echo json_encode(array('status'=>1));
		}else{
			echo json_encode(array('status'=>0, 'message'=>'路由不存在'));
		}
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from('actions', 'a')
			->joinLeft('categories', 'c', 'a.cat_id = c.id', 'title AS cat_title')
			->joinLeft('actions', 'pa', 'a.parent = pa.id', 'router AS parent_router,title AS parent_title')
			->joinLeft('categories', 'pc', 'pa.cat_id = pc.id', 'title AS parent_cat_title')
			->order('a.cat_id');
		if($this->input->get('cat_id')){
			$sql->where(array(
				'a.cat_id = ?'=>$this->input->get('cat_id', 'intval'),
			));
		}
		
		if($this->input->get('router')){
			$sql->where(array(
				'a.router LIKE ?'=>$this->input->get('router').'%',
			));
		}
		$this->view->listview = new ListView($sql);
	}
}