<?php
namespace backend\library;

use fayfox\core\Controller;
use fayfox\core\Uri;
use fayfox\models\tables\Users;
use fayfox\models\File;
use fayfox\core\Response;
use fayfox\core\HttpException;

class ToolsController extends Controller{
	public $layout_template = 'admin';
	/**
	 * 当前用户id（users表中的ID）
	 * @var int
	 */
	public $current_user = 0;
	
	public function __construct(){
		parent::__construct();
		//重置session_namespace
		$this->config->set('session_namespace', $this->config->get('session_namespace').'_admin');
		
		$this->layout->current_directory = '';
		$this->layout->subtitle = '';
	}
	
	public function isLogin(){
		//验证session中是否有值
		if(!$this->session->get('username')){
			Response::redirect('admin/login/index', array('redirect'=>base64_encode($this->view->url(Uri::getInstance()->router, $this->input->get()))));
		}
		if($this->session->get('role') != Users::ROLE_SUPERADMIN){
			throw new HttpException('仅超级管理员可访问此模块', 500);
		}
		//设置当前用户id
		$this->current_user = $this->session->get('id');
	}
	
	public function getApps(){
		$app_dirs = File::getFileList(APPLICATION_PATH.'..');
		$apps = array();
		foreach($app_dirs as $app){
			$apps[] = $app['name'];
		}
		return $apps;
	}
}