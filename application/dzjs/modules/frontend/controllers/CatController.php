<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
use fayfox\models\Category;
use fayfox\core\Response;
use fayfox\core\Sql;
use fayfox\common\ListView;
use fayfox\models\tables\Posts;
class CatController extends FrontController{
    public function index()
    {
        $cat_id = $this->input->get('id', 'intval');
        if ($cat_id == 10000)
        {
            Response::redirect('post/3395');
        }
        $cat = Category::model()->get($cat_id);

        $this->layout->title = $cat['title'];
        if (!$cat){
            Response::showError('您访问的页面不存在！',404,'404');
        }
        
        $this->view->cat = $cat;
        
        $sql = new Sql();
        $sql->from('posts','p','id,title,publish_time')
			->joinLeft('categories', 'c', 'p.cat_id = c.id')
			->order('p.is_top DESC, p.sort, p.publish_time DESC')
			->where(array(
				'c.left_value >= '.$cat['left_value'],
				'c.right_value <= '.$cat['right_value'],
				'p.deleted = 0',
				'p.status = '.Posts::STATUS_PUBLISH,
				'p.publish_time < '.$this->current_time, 
			));
        $this->view->listview = new ListView($sql,array(
            'pageSize'  => 12,
            'reload'    => $this->view->url('cat/'.$cat['id']),
        ));
        
        
        $this->view->render();    
    }
}