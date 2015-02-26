<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
use fayfox\models\Page;
class PageController extends FrontController{
    public function item(){
        $id = $this->input->get('id', 'intval');
        $this->view->about = Page::model()->get($id);
        
        $this->view->render();
    }
}