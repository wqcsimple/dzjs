<?php
use fayfox\models\Menu;
use fayfox\helpers\Html;

$menu = Menu::model()->getTree('_cddx_top');
$menu = Menu::model()->renderLink($menu);
?>
<nav class="g-nav">
	<div class="w1000">
		<ul>
		<?php
			//文章分类列表
			foreach($menu as $m){
				echo '<li class="nav-i">', Html::link($m['title'], $m['link'], array(
					'class'=>'nav-p',
					'title'=>false,
					'target'=>$m['target'] ? $m['target'] : false,
				));
				if(!empty($m['children'])){
					echo '<ul class="nav-c">';
					foreach($m['children'] as $m2){
						echo '<li>', Html::link($m2['title'], $m2['link'], array(
							'title'=>false,
							'target'=>$m2['target'] ? $m2['target'] : false,
						)), '</li>';
					}
					echo '</ul>';
				}
			}
			echo '</li>';
		?>
		</ul>
	</div>
</nav>
<script>
$(function(){
	$('.g-nav').on('mouseover', '.nav-i', function(){
		$(this).find('.nav-c').slideDown('fast');
	});
	$('.g-nav').on('mouseleave', '.nav-i', function(){
		$(this).find('.nav-c').slideUp('fast');
	});
});
</script>