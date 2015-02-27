<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
use fayfox\models\Page;
class PageController extends FrontController{
    public function item(){
        $id = $this->input->get('id');
        if (is_numeric($id)){
            $id = intval($id);
            $this->view->about = Page::model()->get($id);
        }else{
            $alias = trim($id);
            $this->view->about = Page::model()->getByAlias($alias);
        }
        
        
        
        $this->view->render();
    }
    
   
}