<?php
namespace backend\modules\admin\controllers;

use fayfox\core\Controller;
use fayfox\models\User;
use fayfox\models\Log;
use fayfox\core\Response;
use fayfox\models\tables\Logs;
use fayfox\core\Loader;

class LoginController extends Controller{
	public function __construct(){
		parent::__construct();
		$this->config->set('session_namespace', $this->config->get('session_namespace').'_admin');
	}
	
	public function index(){
		if($this->input->post()){
			//获得用户名对应的密码后缀字母
			$result = User::model()->adminLogin($this->input->post('username'), $this->input->post('password'));
			if($result['status']){
				Log::set('admin:action:login.success', array('fmac'=>isset($_COOKIE['fmac']) ? $_COOKIE['fmac'] : ''));
				if($this->input->get('redirect')){
					header('location:'.base64_decode($this->input->get('redirect')));
					die;
				}else{
					Response::redirect('admin/index/index');
				}
			}else{
				Log::set('admin:action:login.fail', array(
					'error_code'=>$result['error_code'],
					'username'=>$this->input->post('username'),
					'password'=>$this->input->post('password'),
				), Logs::TYPE_WARMING);
				$this->view->error = $result['message'];
			}
		}
		
		//引入IP地址库
		Loader::vendor('IpLocation/IpLocation.class');
		$this->view->iplocation = new \IpLocation();
		
		$this->view->render('index');
	}
	
	public function logout(){
		$this->session->remove();
		Response::redirect('admin/login/index');
	}
}