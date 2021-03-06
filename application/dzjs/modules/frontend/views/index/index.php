<?php 
use fayfox\helpers\Html;
use fayfox\helpers\String;
use fayfox\core\Widget;

?>
<!-- 焦点图 -->
<link rel="stylesheet" href="<?php echo $this->staticFile('css/index_focus.css')?>">
<div class="bd">
	<?php F::app()->widget->load('index-top-banner')?>
</div>
<!--焦点图 end-->
<!-- body -->
<div class="in_body">
	<div class="left">
		        <?php F::app()->widget->load('index-box-1');?>
		<!--gg end-->
				<?php F::app()->widget->load('index-box-2')?>
	</div>
	<div class="right">
		<div class="jxdw">
			<div class="bt"><span><i class="icon-users"></i><?php echo $about['title']?></span></div>
			<div class="index-page">
				<a href="<?php echo $this->url('page/'.$about['id'])?>" title="课本"><?php echo Html::img($about['thumbnail'])?></a>
				<?php echo String::niceShort($about['content'], 150)?>
				<br />
				<a href="<?php echo $this->url('page/'.$about['id'])?>" class="look" title="查看详细">[查看详细]</a>
			</div>
		</div>
		<?php F::app()->widget->load('index-box-3')?>
		<?php F::app()->widget->load('index-box-4')?>
	</div>
	<!--image view-->
	<div class="image-view">
		<?php F::app()->widget->load('index-bottom-gallery')?>
	</div>
	
 <script type="text/javascript" src="<?php echo $this->staticFile('js/jquery.kxbdmarquee.js')?>"></script>
<script>
	$(function(){
		/* 图片展示左右轮播 */
		$(".wrap").kxbdMarquee({
					'direction':'left',
					'scrollDelay':20,
					'loop'    :0,
					'isEqual' :true,
				});
	    /* 最新公告上下轮播 */
		$(".gg .bd").kxbdMarquee({
			     'direction':'up',
			     'scrollDelay':50,		//时长		     
			});
	});
</script>
	<!--image view end-->
	
</div>
<!-- body end -->
