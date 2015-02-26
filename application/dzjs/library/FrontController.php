<?php
namespace dzjs\library;

use fayfox\core\Controller;
use fayfox\helpers\RequestHelper;
use fayfox\models\tables\SpiderLogs;

class FrontController extends Controller{
    public $layout_template = 'frontend';  //定义默认模板
    public $current_user = 0;
    
    public function __construct(){
        parent::__construct();
        
        //设置当前用户ID
        $this->current_user = $this->session->get('id', 0);
        
        if ($spider = RequestHelper::isSpider()){//如果是蜘蛛，就记录蜘蛛日志
            SpiderLogs::model()->insert(array(
                'spider'        => $spider,
                'url'           => 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'],
                'user_agent'    => $_SERVER['HTTP_USER_AGENT'],
                'ip_int'        => RequestHelper::ip2int($this->ip),
                'create_time'   => $this->current_time,
            ));
        }
    }
}