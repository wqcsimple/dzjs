<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
use fayfox\models\Post;

class PostController extends FrontController{
    public function item(){
        $id = $this->input->get('id', 'intval');
        $content = Post::model()->get($id);
        
        $this->layout->title = $content['title'];
        
      
        
        $this->view->content = $content;
        
        $this->view->render();
    }
}