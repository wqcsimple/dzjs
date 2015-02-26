<?php
/**
 * 若具体application中存在此配置文件，则配置项会被合并
 * 
 * 该配置文件用于定义网站url的扩展名，默认扩展名在main.php中配置
 * 所有数组项均会被转为正则表达式进行匹配，转换规则
 *     / => \/
 *     * => .*
 */
return array(
	'.js'=>array('tools/analyst'),
	''=>array('file/download*', '/', 'admin/*', 'install/*', 'tools*', 'a', 'file/pic*', 'file/vcode*', 'redirect*'),
);