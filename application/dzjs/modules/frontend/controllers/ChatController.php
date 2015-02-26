<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
class ChatController extends FrontController{
    public function index(){
        $this->layout->title = '互动平台';
        
        $this->view->render();
    }
}