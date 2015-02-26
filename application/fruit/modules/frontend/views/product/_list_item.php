<?php
use fayfox\helpers\Html;
use fayfox\models\File;
?>
<li>
	<div class="inner">
		<figure><?php echo Html::img($data['thumbnail'], File::PIC_ZOOM, array(
			'dw'=>362,
			'dh'=>240,
		))?></figure>
		<div class="mask"></div>
		<h2><?php echo Html::link($data['title'], array(
			'product/'.$data['id']
		))?></h2>
		<div class="abstract"><?php echo Html::encode($data['abstract'])?></div>
	</div>
</li>