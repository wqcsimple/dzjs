<?php
use fayfox\helpers\Html;
?>
<div class="panel">
	<div class="panel-header">
		<h2>最近更新</h2>
	</div>
	<div class="panel-body">
		<ul><?php foreach($last_modified_cats as $c){
			echo Html::link($c['title'], array('guide/'.$c['alias']), array(
				'wrapper'=>'li',
			));
		}?></ul></div>
</div>