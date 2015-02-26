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
			<div class="bt"><span><?php echo $about['title']?></span></div>
			<div class="index-page">
				<a href="" title="课本"><img src="<?php echo $this->staticFile('images/g.jpg') ?>" alt=""></a>
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
		
		<div class="bt"><span><a href="">活动图片</a></span><p><a href="">更多...</a></p></div>
		<div class="bd" id="bd">
			<div class="wrap">
				<ul>
					<li>
						<div class="pic"><a href=""><img src="<?php echo $this->staticFile('images/pic1.jpg') ?>" alt=""></a></div>
						<div class="title"><a href="##">图1</a></div>
					</li>
					<li>
						<div class="pic"><a href=""><img src="<?php echo $this->staticFile('images/pic2.jpg') ?>" alt=""></a></div>
						<div class="title"><a href="##">图2</a></div>
					</li>
					<li>
						<div class="pic"><a href=""><img src="<?php echo $this->staticFile('images/pic3.jpg') ?>" alt=""></a></div>
						<div class="title"><a href="##">图3</a></div>
					</li>
					<li>
						<div class="pic"><a href=""><img src="<?php echo $this->staticFile('images/pic4.jpg') ?>" alt=""></a></div>
						<div class="title"><a href="##">图4</a></div>
					</li>
					<li>
						<div class="pic"><a href=""><img src="<?php echo $this->staticFile('images/pic5.jpg') ?>" alt=""></a></div>
						<div class="title"><a href="##">图5</a></div>
					</li>
					<li>
						<div class="pic"><a href=""><img src="<?php echo $this->staticFile('images/pic6.jpg') ?>" alt=""></a></div>
						<div class="title"><a href="##">图6</a></div>
					</li>
					<li>
						<div class="pic"><a href=""><img src="<?php echo $this->staticFile('images/pic7.jpg') ?>" alt=""></a></div>
						<div class="title"><a href="##">图7</a></div>
					</li>
				</ul>
			</div>
		</div>
	</div>
</script> <script type="text/javascript" src="<?php echo $this->staticFile('js/jquery.kxbdmarquee.js')?>"></script>
<script>
	$(function(){

		$(".wrap").kxbdMarquee({
					'direction':'left',
					'scrollDelay':20,
					'loop'    :0,
					'isEqual' :true,
				});
	});
			
</script>
	<!--image view end-->

</div>

<!-- body end -->












