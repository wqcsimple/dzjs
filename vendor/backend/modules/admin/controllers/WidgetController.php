<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;
use fayfox\models\tables\Widgets;
use fayfox\helpers\String;
use fayfox\models\tables\Actionlogs;
use fayfox\core\Sql;
use fayfox\common\ListView;
use fayfox\models\File;
use fayfox\core\Response;
use fayfox\models\Setting;
use fayfox\core\HttpException;

class WidgetController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'site';
	}
	
	public function index(){
		$this->layout->subtitle = '小工具';
		
		$widget_instances = array();
		
		//获取当前application下的widgets
		$app_widgets = File::getFileList(APPLICATION_PATH . 'widgets');
		foreach($app_widgets as $w){
			$widget_instances[] = $this->widget->get($w['name'], true);
		}
		
		//获取系统公用widgets
		$common_widgets = File::getFileList(SYSTEM_PATH . 'fayfox' . DS . 'widgets');
		foreach($common_widgets as $w){
			$widget_instances[] = $this->widget->get('fayfox/'.$w['name'], true);
		}
		
		$this->view->widgets = $widget_instances;
		
		$this->view->render();
	}
	
	public function edit(){
		$id = $this->input->get('id', 'intval');

		$widget = Widgets::model()->find($id, 'widget_name');
		$widget_obj = $this->widget->get($widget['widget_name'], true);
		
		$this->form('widget')->setRules(array(
			array('f_widget_alias', 'string', array('max'=>255,'special_characters'=>false)),
			array('f_widget_description', 'string', array('max'=>255)),
			array('f_widget_alias', 'unique', array('table'=>'widgets', 'field'=>'alias', 'except'=>'id', 'ajax'=>array('admin/widget/is-alias-not-exist'))),
			
		))->setLabels(array(
			'f_widget_alias'=>'别名',
			'f_widget_description'=>'描述',
		));
		
		$widget_admin = $this->widget->get($widget['widget_name'], true);
		$this->form('widget')->setRules($widget_admin->rules())
			->setLabels($widget_admin->labels())
			->setFilters($widget_admin->filters());
		
		if($this->input->post()){
			if($this->form('widget')->check()){
				Widgets::model()->update(array(
					'alias'=>$this->input->post('f_widget_alias'),
					'description'=>$this->input->post('f_widget_description'),
					'enabled'=>$this->input->post('f_widget_enabled') ? 1 : 0,
				), $id);
				if(method_exists($widget_obj, 'onPost')){
					$widget_obj->onPost();
				}
			}else{
				$this->showDataCheckError($this->form('widget')->getErrors());
			}
		}
		
		$widget = Widgets::model()->find($id);
		$this->view->widget = $widget;
		if($widget['options']){
			$this->view->widget_data = json_decode($widget['options'], true);
			$this->form('widget')->setData($this->view->widget_data);
		}else{
			$this->view->widget_data = array();
		}
		
		$this->view->widget_admin = $widget_admin;
		$this->layout->subtitle = '编辑小工具  - '.$this->view->widget_admin->title;
		
		$this->view->render();
	}
	
	/**
	 * 加载一个widget
	 */
	public function render(){
		if($this->input->get('name')){
			$widget_obj = $this->widget->get($this->input->get('name', 'trim'));
			if($widget_obj == null){
				if($this->input->isAjaxRequest()){
					echo json_encode(array(
						'status'=>0,
						'message'=>'Widget不存在或已被删除',
					));
					die;
				}else{
					throw new HttpException('Widget不存在或已被删除');
				}
			}
			$action = String::hyphen2case($this->input->get('action', 'trim', 'index'), false);
			if(method_exists($widget_obj, $action)){
				$widget_obj->{$action}($this->input->get());
			}else if(method_exists($widget_obj, $action.'Action')){
				$widget_obj->{$action.'Action'}($this->input->get());
			}else{
				if($this->input->isAjaxRequest()){
					echo json_encode(array(
						'status'=>0,
						'message'=>'Widget方法不存在',
					));
				}else{
					throw new HttpException('Widget方法不存在');
				}
			}
		}else{
			if($this->input->isAjaxRequest()){
				echo json_encode(array(
					'status'=>0,
					'message'=>'不完整的请求',
				));
			}else{
				throw new HttpException('不完整的请求');
			}
		}
	}
	
	public function createInstance(){
		if($this->input->post()){
			$widget_instance_id = Widgets::model()->insert(array(
				'widget_name'=>$this->input->post('widget_name'),
				'alias'=>$this->input->post('alias') ? $this->input->post('alias') : uniqid(),
				'description'=>$this->input->post('description'),
			));
			$this->actionlog(Actionlogs::TYPE_WIDGET, '创建了一个小工具实例', $widget_instance_id);
			
			Response::output('success', '小工具实例创建成功', array('admin/widget/edit', array(
				'id'=>$widget_instance_id,
			)));
		}else{
			throw new HttpException('不完整的请求');
		}
	}
	
	public function instances(){
		$this->layout->subtitle = '小工具实例';
		
		//自定义参数
		$this->layout->_setting_panel = '_setting_instance';
		$_setting_key = 'admin_widget_instances';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'page_size'=>20,
		);
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from('widgets')
			->order('id DESC');
		$this->view->listview = new ListView($sql, array(
			'pageSize'=>$this->form('setting')->getData('page_size', 20),
		));
		
		$this->view->render();
	}
	
	public function removeInstance(){
		$id = $this->input->get('id', 'intval');
		Widgets::model()->delete($this->input->get('id', 'intval'));
		$this->actionlog(Actionlogs::TYPE_WIDGET, '删除了一个小工具实例', $id);

		Response::output('success', array(
			'message'=>'一个小工具实例被删除',
		));
	}
	
	public function isAliasNotExist(){
		if(Widgets::model()->fetchRow(array(
			'alias = ?'=>$this->input->post('value', 'trim'),
			'id != ?'=>$this->input->request('id', 'intval', false)
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