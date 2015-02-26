<?php
return array(
	'/^page\/(\d+)$/'=>'page/item/id/$1',
	'/^product\/(\d+)$/'=>'product/item/id/$1',
	'/^product\/([\w-]+)$/'=>'product/index/cat/$1',
);