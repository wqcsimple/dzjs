<?php
use fayfox\models\Post;

foreach($posts as $p){
	$this->renderPartial('_panel', array(
		'id'=>$p['id'],
		'title'=>$p['title'],
		'body'=>Post::formatContent($p),
		'file_link'=>Post::model()->getPropValueByAlias('file_link', $p['id']),
	));
}