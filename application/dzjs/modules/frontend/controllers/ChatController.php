<?php
namespace dzjs\modules\frontend\controllers;

use dzjs\library\FrontController;
use fayfox\models\User;
use fayfox\helpers\RequestHelper;
use fayfox\models\tables\Users;
use fayfox\models\tables\Messages;
use fayfox\models\Message;
use fayfox\core\Response;
use fayfox\core\Sql;
use fayfox\common\ListView;

class ChatController extends FrontController{
    public function index(){
        $this->layout->title = '互动平台';
        $this->form()->setData($this->input->get());
        
        
        $sql = new Sql();
        $sql->from('messages', 'm')
            ->joinLeft('users', 'u', 'm.user_id = u.id', 'realname,username,avatar,nickname')
			->joinLeft('users', 'u2', 'm.target = u2.id', 'username AS target_username,nickname AS target_nickname')
			->where(array(
			    'm.type = '.Messages::TYPE_USER_MESSAGE,
			    'm.root = 0',
			    'm.deleted = 0',
			    'm.status = '.Messages::STATUS_APPROVED,
			))
			->order('id DESC');
	    
		$listview = new ListView($sql, array(
		    'pageSize' => 10,
		    'reload'    => $this->view->url('chat'),
		));
		$this->view->listview = $listview;
        
        
        $this->view->render();
    }
    
    public function create(){
        if ($this->input->post('realname','trim') && $this->input->post('content','trim')){
            //虚构一个用户
            $user_id = Users::model()->insert(array(
                'reg_time' => $this->current_time,
                'reg_ip'   => RequestHelper::ip2int(RequestHelper::getIP()),
                'status'   => Users::STATUS_NOT_VERIFIED,
                'nickname' => $this->input->post('realname', 'trim'),
                'realname' => $this->input->post('realname', 'trim'),
            ));
            
            $content = $this->input->post('content', 'trim', '');
            $type = Messages::TYPE_USER_MESSAGE;
            $parent = $this->input->post('parent', 'intval', 0);
            $message_id = Message::model()->create(2, $content, $type, $parent, Messages::STATUS_APPROVED, $user_id);
            
            $message = Message::model()->get($message_id);
            Response::output('success', array(
                    'message'  => '发布留言成功！',
                    'data'  =>$message,            
            ));
        }else{
            Response::output('error','信息不完整');
        }
    }
}