<?php
namespace backend\modules\admin\controllers;

use backend\library\AdminController;
use fayfox\models\tables\Files;
use fayfox\models\File;
use fayfox\models\Setting;
use fayfox\core\Sql;
use fayfox\common\ListView;
use fayfox\helpers\Image;
use fayfox\models\Qiniu;
use fayfox\core\HttpException;
use fayfox\core\Validator;
use fayfox\core\Response;
use fayfox\models\tables\Actionlogs;

class FileController extends AdminController{
	public function __construct(){
		parent::__construct();
		$this->layout->current_directory = 'file';
	}
	
	public function upload(){
		set_time_limit(0);
		
		$target = $this->input->get('t');
		$type = 0;
		//传入非指定target的话，清空这个值
		if($target == 'posts'){
			$type = Files::TYPE_POST;
		}else if($target == 'pages'){
			$type = Files::TYPE_PAGE;
		}else if($target == 'goods'){
			$type = Files::TYPE_GOODS;
		}else if($target == 'cat'){
			$type = Files::TYPE_CAT;
		}else if($target == 'widget'){
			$type = Files::TYPE_WIDGET;
		}else if($target == 'avatar'){
			$type = Files::TYPE_AVATAR;
		}else if($target == 'exam'){
			$type = Files::TYPE_EXAM;
		}else{
			$target = 'other';
		}
		
		$private = !!$this->input->get('p');
		$result = File::model()->upload($target, $type, $private);
		if($this->input->get('CKEditorFuncNum')){
			echo "<script>window.parent.CKEDITOR.tools.callFunction({$this->input->get('CKEditorFuncNum')}, '{$result['src']}', '');</script>";
		}else{
			echo json_encode($result);
		}
	}
	
	public function doUpload(){
		$this->layout->subtitle = '上传文件';
		$this->view->render();
	}
	
	public function remove(){
		if($file_id = $this->input->get('id', 'intval')){
			$file = Files::model()->find($file_id);
			if($file['qiniu']){//如果已经上传到七牛，则先从七牛删除
				Qiniu::model()->delete($file);
			}
			
			Files::model()->delete($file_id);
			@unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . $file['file_ext']);
			@unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . '-100x100.jpg');
			Response::output('success', '删除成功');
		}else{
			Response::output('error', '参数不完整');
		}
	}
	
	public function index(){
		$this->layout->subtitle = '文件';
		
		$this->layout->_setting_panel = '_setting_index';
		$_setting_key = 'admin_file_index';
		$_settings = Setting::model()->get($_setting_key);
		$_settings || $_settings = array(
			'cols'=>array('client_name', 'file_type', 'file_size', 'username', 'upload_time'),
			'display_name'=>'username',
			'display_time'=>'short',
			'page_size'=>10,
		);
		
		//如果未配置七牛参数，则强制不显示七牛那一列
		if(!$this->config->get('*', 'qiniu')){
			foreach($_settings['cols'] as $k => $v){
				if($v == 'qiniu'){
					unset($_settings['cols'][$k]);
					break;
				}
			}
		}
		
		$this->form('setting')->setModel(Setting::model())
			->setJsModel('setting')
			->setData($_settings)
			->setData(array(
				'_key'=>$_setting_key,
			));
		
		$sql = new Sql();
		$sql->from('files', 'f')
			->joinLeft('users', 'u', 'u.id = f.user_id', 'username,nickname,realname')
			->order('id DESC');
		
		if($this->input->get('keywords')){
			$sql->where(array('f.client_name LIKE ?'=>'%'.$this->input->get('keywords').'%'));
		}
		
		if($this->input->get('type')){
			$sql->where(array('f.type = ?'=>$this->input->get('type', 'intval')));
		}
		
		if($this->input->get('qiniu') !== '' && $this->input->get('qiniu') !== null){
			$sql->where(array('f.qiniu = ?'=>$this->input->get('qiniu', 'intval')));
		}
		
		if($this->input->get('start_time')){
			$sql->where(array("f.upload_time > ?"=>$this->input->get('start_time', 'strtotime')));
		}
		if($this->input->get('end_time')){
			$sql->where(array("f.upload_time < ?"=>$this->input->get('end_time', 'strtotime')));
		}
		
		$this->view->listview = new ListView($sql, array(
			'pageSize'=>$this->form('setting')->getData('page_size', 20),
			'emptyText'=>'<tr><td colspan="'.(count($this->form('setting')->getData('cols')) + 3).'" align="center">无相关记录！</td></tr>',
		));
		
		$this->view->render();
	}
	
	public function batch(){
		$ids = $this->input->post('ids', 'intval');
		$action = $this->input->post('batch_action');
		if(empty($action)){
			$action = $this->input->post('batch_action_2');
		}
		switch($action){
			case 'remove':
				$affected_rows = 0;
				foreach($ids as $id){
					$file = Files::model()->find($id);
					if($file){
						if($file['qiniu']){//如果已经上传到七牛，则先从七牛删除
							Qiniu::model()->delete($file);
						}
							
						Files::model()->delete($id);
						@unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . $file['file_ext']);
						@unlink((defined('NO_REWRITE') ? './public/' : '').$file['file_path'] . $file['raw_name'] . '-100x100.jpg');
						$affected_rows++;
					}
				}
				
				$this->actionlog(Actionlogs::TYPE_FILE, '批处理：'.$affected_rows.'个文件被删除');
				Response::output('success', $affected_rows.'个文件被删除');
			break;
		}
	}
	
	public function download(){
		if($file_id = $this->input->get('id', 'intval')){
			if($file = Files::model()->find($file_id)){
				//可选下载文件名格式
				if($this->input->get('name') == 'date'){
					$filename = date('YmdHis', $file['upload_time']).$file['file_ext'];
				}else if($this->input->get('name') == 'timestamp'){
					$filename = $file['upload_time'].$file['file_ext'];
				}else if($this->input->get('name') == 'client_name'){
					$filename = $file['client_name'];
				}else{
					$filename = $file['raw_name'].$file['file_ext'];
				}
				
				Files::model()->inc($file_id, 'downloads', 1);
				$data = file_get_contents((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
				if (strpos($_SERVER['HTTP_USER_AGENT'], "MSIE") !== FALSE){
					header('Content-Type: "'.$file['file_type'].'"');
					header('Content-Disposition: attachment; filename="'.$filename.'"');
					header('Expires: 0');
					header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
					header("Content-Transfer-Encoding: binary");
					header('Pragma: public');
					header("Content-Length: ".strlen($data));
				}else{
					header('Content-Type: "'.$file['file_type'].'"');
					header('Content-Disposition: attachment; filename="'.$filename.'"');
					header("Content-Transfer-Encoding: binary");
					header('Expires: 0');
					header('Pragma: no-cache');
					header("Content-Length: ".strlen($data));
				}
				die($data);
			}else{
				throw new HttpException('文件不存在');
			}
		}else{
			throw new HttpException('参数不正确', 500);
		}
	}

	public function pic(){
		$validator = new Validator();
		$check = $validator->check(array(
			array(array('f'), 'required'),
			array(array('t'), 'range', array('range'=>array('1', '2', '3', '4'))),
			array(array('x','y', 'dw', 'dh', 'w', 'h'), 'int'),
		));
		
		if($check !== true){
			//@todo输出一张参数异常的图片
			print_r($check);die;
		}
		
		//显示模式
		$t = $this->input->get('t', 'intval', 1);
		
		//文件名或文件id号
		$f = $this->input->get('f');
		if(is_numeric($f)){
			if($f == 0){
				$file = false;
			}else{
				$file = Files::model()->find($f);
			}
		}else{
			$file = Files::model()->fetchRow(array('raw = ?'=>$f));
		}

		if(isset($_SERVER['HTTP_IF_NONE_MATCH']) && $file['raw_name'] == $_SERVER['HTTP_IF_NONE_MATCH']){
			header('HTTP/1.1 304 Not Modified');
			die;
		}
		
		//设置缓存
		header("Expires: Sat, 26 Jul 2020 05:00:00 GMT");
		header('Last-Modified: '.gmdate('D, d M Y H:i:s', $file['upload_time']).' GMT');
		header("Cache-control: max-age=3600");
		header("Pragma: cache");
		header('Etag:'.$file['raw_name']);
		
		switch ($t) {
			case 1:
				//直接输出图片
				$this->view_pic($file);
				break;
			case 2:
				//输出图片的缩略图
				$this->view_thumbnail($file);
				break;
			case 3:
				/**
				 * 根据起始坐标，宽度及宽高比裁剪后输出图片
				 * @param $_GET['x'] 起始点x坐标
				 * @param $_GET['y'] 起始点y坐标
				 * @param $_GET['dw'] 输出图像宽度
				 * @param $_GET['dh'] 输出图像高度
				 * @param $_GET['w'] 截图图片的宽度
				 * @param $_GET['h'] 截图图片的高度
				 */
				$this->view_cut($file);
				break;
			case 4:
				/**
				 * 根据给定的宽高对图片进行裁剪后输出图片
				 * @param $_GET['dw'] 输出图像宽度
				 * @param $_GET['dh'] 输出图像高度
				 * 若仅指定高度或者宽度，则会按比例缩放
				 * 若均不指定，则默认为200*200
				 */
				$this->view_zoom($file);
				break;
		
			default:
				;
				break;
		}
	}
	
	private function view_pic($file){
		if($file !== false){
			if(file_exists((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext'])){
				header('Content-type: '.$file['file_type']);
				readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
			}else{
				header('Content-type: image/jpeg');
				readfile(BASEPATH . '/images/no-image.jpg');
			}
		}else{
			header('Content-type: image/jpeg');
			readfile(BASEPATH . '/images/no-image.jpg');
		}
	}
	
	private function view_thumbnail($file){
		if($file !== false){
			header('Content-type: '.$file['file_type']);
			readfile((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].'-100x100.jpg');
		}else{
			$img = file_get_contents('./images/thumbnail.jpg');
			header('Content-type: image/jpeg');
			echo $img;
		}
	}
	
	private function view_cut($file){
		//x坐标位置
		$x = $this->input->get('x', 'intval', 0);
		//y坐标
		$y = $this->input->get('y', 'intval', 0);
		//输出宽度
		$dw = $this->input->get('dw', 'intval', 0);
		//输出高度
		$dh = $this->input->get('dh', 'intval', 0);
		//选中部分的宽度
		$w = $this->input->get('w', 'intval');
		if(!$w)throw new HttpException('不完整的请求', 500);
		//选中部分的高度
		$h = $this->input->get('h', 'intval');
		if(!$h)throw new HttpException('不完整的请求', 500);
		
		if($file !== false){
			$img = Image::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
		
			if($dw == 0){
				$dw = $w;
			}
			if($dh == 0){
				$dh = $h;
			}
			$img = Image::cut($img, $x, $y, $w, $h);
			$img = Image::zoom($img, $dw, $dh);
		
			header('Content-type: '.$file['file_type']);
			switch ($file['file_type']) {
				case 'image/gif':
					imagegif($img);
					break;
				case 'image/jpeg':
				case 'image/jpg':
					imagejpeg($img);
					break;
				case 'image/png':
					imagepng($img);
					break;
				default:
					imagejpeg($img);
					break;
			}
		}else{
			//图片不存在，显示一张默认图片吧
		}
	}
	
	private function view_zoom($file){
		$spares = $this->config->get('spares');
		$spare = $spares[$this->input->get('s', null, 'default')];
		//输出宽度
		$dw = $this->input->get('dw', 'intval');
		//输出高度
		$dh = $this->input->get('dh', 'intval');
		
		if($dw && !$dh){
			$dh = $dw * ($file['image_height'] / $file['image_width']);
		}else if($dh && !$dw){
			$dw = $dh * ($file['image_width'] / $file['image_height']);
		}else if(!$dw && !$dh){
			$dw = 200;
			$dh = 200;
		}
		
		if($file !== false){
			$img = Image::getImage((defined('NO_REWRITE') ? './public/' : '').$file['file_path'].$file['raw_name'].$file['file_ext']);
		
			$img = Image::zoom($img, $dw, $dh);
		
			header('Content-type: '.$file['file_type']);
			switch ($file['file_type']) {
				case 'image/gif':
					imagegif($img);
					break;
				case 'image/jpeg':
				case 'image/jpg':
					imagejpeg($img);
					break;
				case 'image/png':
					imagepng($img);
					break;
				default:
					imagejpeg($img);
					break;
			}
		}else{
			$img = Image::getImage($spare);
			header('Content-type: image/jpeg');
			$img = Image::zoom($img, $dw, $dh);
			imagejpeg($img);
		}
	}
}