<div class="pagenav">
	<span class="pages">
		<?php echo $listview->currentPage?>
		/
		<?php echo $listview->totalPages?>
	</span>
	<?php for($i = 1; $i <= $listview->totalPages; $i++){?>
		<?php if($i == $listview->currentPage){?>
			<span class="current"><?php echo $i?></span>
		<?php }else{?>
			<a href="<?php echo $listview->reload?>?page=<?php echo $i?>" class="page"><?php echo $i?></a>
		<?php }?>
	<?php }?>
	<?php if($listview->currentPage < $listview->totalPages){?>
		<a href="<?php echo $listview->reload?>?page=<?php echo $listview->currentPage + 1?>" class="nextpostslink">&raquo;</a>
	<?php }?>
</div>