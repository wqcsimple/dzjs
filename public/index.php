<?php
use fayfox\core\Bootstrap;
use fayfox\core\Hook;

session_start();//开启session
define('START', microtime(true));
define('BASEPATH', realpath(__DIR__).DIRECTORY_SEPARATOR);//定义程序根目录绝对路径
define('APPLICATION', isset($_SESSION['__app']) ? $_SESSION['__app'] : 'dzjs');

require __DIR__.'/_init.php';
require_once __DIR__ . '/util.php';

$bootstrap = new Bootstrap();
if($bootstrap->config('hook')){
	Hook::getInstance()->call('before_system');
}
$bootstrap->init();