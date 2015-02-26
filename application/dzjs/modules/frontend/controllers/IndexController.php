<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
use fayfox\models\tables\Pages;

class IndexController extends FrontController{
   public function __construct(){
       parent::__construct();
       
       $this->layout->title = '首页';
       $this->layout->keywords = '';
	   $this->layout->description = '';
   } 
   
   public function index(){
       $this->view->about = Pages::model()->fetchRow("alias = 'jxdw'");//教学队伍介绍
       $this->view->render();
   }
}