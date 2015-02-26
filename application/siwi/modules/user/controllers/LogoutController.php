<?php
namespace siwi\modules\user\controllers;

use siwi\library\UserController;
use fayfox\models\User;
use fayfox\core\Response;

class LogoutController extends UserController{
	public function index(){
		User::model()->logout();
		
		Response::redirect('index');
	}
}