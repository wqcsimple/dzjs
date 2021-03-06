<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;
use fayfox\models\tables\Users;
use fayfox\models\tables\UserNotifications;
use fayfox\models\tables\Actionlogs;
use fayfox\models\tables\Roles;
use fayfox\models\Category;
use fayfox\common\ListView;
use fayfox\core\Response;
use fayfox\helpers\Html;
use fayfox\models\Notification;
use fayfox\core\Sql;

class NotificationController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'notification';
	}
	
	public function create(){
		$this->layout->subtitle = '发送系统消息';
		if($this->input->post()){
			$operators = Users::model()->fetchCol('id', array(
				'role IN (?)'=>$this->input->post('roles', 'intval'),
			));
			$notification_id = Notification::model()->send($operators, $this->input->post('content'), $this->current_user, $this->input->get('cat_id', null, 0));
			
			$this->actionlog(Actionlogs::TYPE_NOTIFICATION, '发送系统信息', $notification_id);
			$this->flash->set('消息发送成功', 'success');
		}
		$this->view->notification_cats = Category::model()->getNextLevel('_system_notification');
		$this->view->roles = Roles::model()->fetchAll('deleted = 0');
		$this->view->render();
	}
	
	public function my(){
		$this->layout->subtitle = '我的消息';
		
		$sql = new Sql();
		$sql->from('user_notifications', 'un', 'id,read')
			->joinLeft('notifications', 'n', 'un.notification_id = n.id', 'content,`from`,publish_time')
			->joinLeft('users', 'u', 'n.`from` = u.id', 'username,nickname,realname')
			->joinLeft('categories', 'c', 'n.cat_id = c.id', 'title AS cat_title')
			->where(array(
				'un.to = '.$this->current_user,
				'n.publish_time <= '.$this->current_time,
				'un.deleted = 0',
			))
			->order('n.publish_time DESC')
		;
		
		$this->view->listview = new ListView($sql, array(
			'emptyText'=>'<tr><td colspan="5" align="center">无相关记录！</td></tr>',
		));
		
		$this->view->render();
	}
	
	public function delete(){
		UserNotifications::model()->update(array('deleted'=>1), "id = {$this->input->get('id', 'intval')}");
		$this->actionlog(Actionlogs::TYPE_NOTIFICATION, '删除系统信息', $this->input->get('id', 'intval'));
		
		Response::output('success', array(
			'message'=>'一条消息被移入回收站 - '.Html::link('撤销', array('admin/notification/undelete', array(
				'id'=>$this->input->get('id', 'intval'),
			))),
			'id'=>$this->input->get('id', 'intval'),
		));
	}
	
	public function undelete(){
		UserNotifications::model()->update(array('deleted'=>0), array('id = ?'=>$this->input->get('id', 'intval')));
		$this->actionlog(Actionlogs::TYPE_NOTIFICATION, '还原系统信息', $this->input->get('id', 'intval'));
		
		Response::output('success', array(
			'message'=>'一条消息被还原',
			'id'=>$this->input->get('id', 'intval'),
		));
	}
	
	public function get(){
		//刷新用户在线信息
		Users::model()->update(array(
			'last_time_online'=>$this->current_time,
		), $this->current_user);
		
		//获取未读消息数
		$sql = new Sql();
		$notifications = $sql->from('user_notifications', 'un', 'id')
			->joinLeft('notifications', 'n', 'un.notification_id = n.id', 'content,publish_time')
			->where(array(
				'un.`read` = 0',
				"un.`to` = {$this->current_user}",
				'un.`deleted` = 0',
				"n.publish_time <= {$this->current_time}",
			))
			->order('n.publish_time DESC')
			->fetchAll();
		
		Response::output('success', array(
			'data'=>$notifications,
		));
	}
	
	public function mute(){
		UserNotifications::model()->update(array('read'=>1), "`to` = {$this->current_user}");
	}
	
	public function cat(){
		$this->layout->subtitle = '消息分类';
		$this->view->cats = Category::model()->getTree('_system_notification');
		$root_node = Category::model()->getByAlias('_system_notification', 'id');
		$this->view->root = $root_node['id'];
	
		if($this->checkPermission('admin/notification/cat-create')){
			$this->layout->sublink = array(
				'uri'=>'#create-cat-dialog',
				'text'=>'添加消息分类',
				'htmlOptions'=>array(
					'class'=>'create-cat-link',
					'data-title'=>'消息分类',
					'data-id'=>$root_node['id'],
				),
			);
		}
	
		$this->view->render();
	}
	
	public function setRead(){
		$id = $this->input->get('id', 'intval');
		$read = $this->input->get('read', 'intval');
		
		UserNotifications::model()->update(array(
			'read'=>$read,
		), $id);
		
		Response::output('success', '一条信息被标记为'.($read ? '已读' : '未读'));
	}
	
	public function batch(){
		$ids = $this->input->post('ids', 'intval');
		$action = $this->input->post('batch_action');
		if(empty($action)){
			$action = $this->input->post('batch_action_2');
		}
		switch($action){
			case 'set-read':
				$affected_rows = UserNotifications::model()->update(array(
					'read'=>1,
				), array(
					'id IN (?)'=>$ids,
				));
				Response::output('success', $affected_rows.'条消息被标记为已读');
			break;
			case 'set-unread':
				$affected_rows = UserNotifications::model()->update(array(
					'read'=>0,
				), array(
					'id IN (?)'=>$ids,
				));
				Response::output('success', $affected_rows.'条消息被标记为未读');
			break;
			case 'delete':
				$affected_rows = UserNotifications::model()->update(array(
					'deleted'=>1,
				), array(
					'id IN (?)'=>$ids,
				));
				Response::output('success', $affected_rows.'条消息被删除');
			break;
		}
	}
}