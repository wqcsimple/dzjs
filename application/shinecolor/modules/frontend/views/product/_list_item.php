<?php
use fayfox\helpers\Html;
use fayfox\models\File;
?>
<div class="product-item <?php if($index % 3 == 0)echo 'last'?>">
	<div class="thumbnail-container"><?php echo Html::link(Html::img($data['thumbnail'], File::PIC_ZOOM, array(
		'dw'=>243,
		'dh'=>183,
	)), array('product/'.$data['id']), array(
		'encode'=>false,
		'title'=>Html::encode($data['title']),
		'alt'=>Html::encode($data['title']),
	))?></div>
	<h2><?php echo Html::link($data['title'], array('product/'.$data['id']))?></h2>
</div>