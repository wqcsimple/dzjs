<?php
return array(
	'/^cat-(\d+)$/'=>'post/index/cat_id/$1',
	'/^post-(\d+)$/'=>'post/item/id/$1',
	'/^post\/(\d+)$/'=>'post/item/id/$1',
	'/^page-(\d+)$/'=>'page/item/id/$1',
);