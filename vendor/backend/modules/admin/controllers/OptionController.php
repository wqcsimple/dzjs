<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;
use fayfox\models\tables\Options;
use fayfox\core\Sql;
use fayfox\common\ListView;
use fayfox\core\Response;
use fayfox\core\HttpException;

class OptionController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'site';
	}
	
	public function create(){
		if($this->input->post()){
			if($this->form()->setModel(Options::model())->check()){
				$data = $this->form()->getFilteredData();
				$data['create_time'] = $this->current_time;
				Options::model()->insert($data);
				
				Response::output('success', array(
					'message'=>'站点参数添加成功',
				), array('admin/option/index'));
			}else{
				Response::output('error', array(
					'message'=>'参数异常',
				), array('admin/option/index'));
			}
		}else{
			Response::output('error', array(
				'message'=>'不完整的请求',
			), array('admin/option/index'));
		}
	}
	
	public function edit(){
		$this->layout->subtitle = '编辑参数';
		$this->layout->sublink = array(
			'uri'=>array('admin/option/index', $this->input->get()),
			'text'=>'添加参数',
		);
		$option_id = $this->input->get('id', 'intval');
		$this->form()->setModel(Options::model());
		if($this->input->post()){
			if($this->form()->check()){
				$data = $this->form()->getFilteredData();
				$data['last_modified_time'] = $this->current_time;
				Options::model()->update($data, array('id = ?'=>$option_id));
				$this->flash->set('一个参数被编辑', 'success');
			}else{
				$this->showDataCheckError($this->form()->getErrors());
			}
		}
		if($option = Options::model()->find($option_id)){
			$this->form()->setData($option);
			$this->view->option = $option;
			
			$this->_setListview();
			
			$this->view->render();
		}else{
			throw new HttpException('无效的ID');
		}
	}
	
	public function index(){
		$this->flash->set('这是一个汇总表，如果您不清楚它的含义，请不要随意修改，后果可能很严重！', 'attention');
		$this->layout->subtitle = '添加参数';
		
		$this->_setListview();
		
		$this->form()->setModel(Options::model());
		
		$this->view->render();
	}
	
	public function remove(){
		Options::model()->delete(array('id = ?'=>$this->input->get('id', 'intval')));
		Response::output('success', array(
			'message'=>'一个参数被永久删除',
		), array('admin/option/index', $this->input->get()));
	}
	
	public function isOptionNotExist(){
		$option_name = $this->input->post('value', 'trim');
		if(Options::model()->fetchRow(array(
			'option_name = ?'=>$option_name,
			'id != ?'=>$this->input->get('id', 'intval', 0),
		))){
			echo json_encode(array(
				'status'=>0,
				'message'=>'参数名已存在',
			));
		}else{
			echo json_encode(array(
				'status'=>1,
			));
		}
	}
	
	/**
	 * 设置右侧列表
	 */
	private function _setListview(){
		$sql = new Sql();
		$sql->from('options');

		if($this->input->get('orderby')){
			$this->view->orderby = $this->input->get('orderby');
			$this->view->order = $this->input->get('order') == 'asc' ? 'asc' : 'desc';
			$sql->order("{$this->view->orderby} {$this->view->order}");
		}else{
			$sql->order('id DESC');
		}
		
		$this->view->listview = new ListView($sql, array(
			'pageSize'=>15,
		));
	}
}