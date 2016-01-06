<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
use fayfox\models\Post;
use fayfox\models\tables\Posts;
use fayfox\core\db\Intact;

class PostController extends FrontController{
    public function item()
    {
        $id = $this->input->get('id', 'intval');
        $content = Post::model()->get($id);
        
        Posts::model()->update(array(
            'last_view_time'  => $this->current_time,
            'views'  => new Intact('views + 1'),
        ), $id);
        
        $this->layout->title = $content['title'];
        
      
        
        $this->view->content = $content;
        
        $this->view->render();
    }
}