<?php
use fayfox\helpers\Html;
?>
<div class="pagenav">
	<span class="pages">
		第
		<?php echo $listview->currentPage?>
		/
		<?php echo $listview->totalPages?>
		页
		&nbsp;
	</span>
	<?php for($i = 1; $i <= $listview->totalPages; $i++){?>
		<?php if($i == $listview->currentPage){?>
			<span class="current"><?php echo $i?></span>
		<?php }else{
			if($i > 1){
				echo Html::link($i, "{$listview->reload}?page={$i}", array(
					'class'=>'page',
				));
			}else{
				echo Html::link($i, $listview->reload, array(
					'class'=>'page',
				));
			}
		}?>
	<?php }?>
	<?php if($listview->currentPage < $listview->totalPages){?>
		<a href="<?php echo $listview->reload?>?page=<?php echo $listview->currentPage + 1?>" class="nextpostslink">&raquo;</a>
	<?php }?>
</div>