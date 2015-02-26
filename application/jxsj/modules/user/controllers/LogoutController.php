<?php
namespace jxsj\modules\user\controllers;

use jxsj\library\UserController;
use fayfox\models\User;
use fayfox\core\Response;

class LogoutController extends UserController{
	public function index(){
		User::model()->logout();
		
		Response::redirect(null);
	}
}