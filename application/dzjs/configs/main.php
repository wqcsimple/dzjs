<?php
/**
 * 该文件的配置项将覆盖外层/config/main.php的配置
* 若不设置则默认使用外面的配置参数
*/
return array(
    /*
     * 数据库参数
*/
    'db'=>array(
        'host'=>'db',					//数据库服务器
        'user'=>'wwhis',							//用户名
        'password'=>'ejsFPe4SLPx5wC1W',							//密码
        'port'=>3306,							//端口
        'dbname'=>'fayfox_dzjs',					//数据库名
        'charset'=>'utf8',						//数据库编码方式
        'table_prefix'=>'fayfox_',				//数据库表前缀
    ),

    /*
     * 在一台服务器上跑多个cms的时候，以此区分session，可以随便设置一个
*/
    'session_namespace'=>'dzjs',
    
    /*
     * 若为true，则页面地步会列出所有被执行的sql语句等信息
     */
    'debug'=> false,

    /*
     * 当前application包含的模块
     */
    'modules'=>array(
        'frontend'
    ),
    
    /*
     * 默认url后缀
     * 可通过config/ext.php配置文件对单独的url再做设置
     */
    'url_suffix'=>'.shtml',
);