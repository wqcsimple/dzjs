<?php 
use fayfox\models\Category;
use fayfox\helpers\Html;
?>	
	<div class="top_line"></div>
	 <div class="topper">
	  <div class="web">
		<div class="search">
			<input type="text" x-webkit-speech="" class="input" name="keyword" id="keyword"/>
			<input type="button" class="ss" value="" onclick="submit()"/>
		</div>
		
		<a href="###">联系我们</a>
		<a href="###">站点地图</a>
		<a href="http://dzjs.ypcol.com/index.asp" target="_blank">旧版主页</a>
	  </div>
	 </div>

	 <div class="menuer">
	 	<div class="menu">
	 		<div class="menu_logo"><a href=""><img src="<?php echo $this->staticFile('images/logo.gif')?>"></a><h1></h1></div>
	 		<div class="menu_menu">
			   <div class="nav">
			    <ul>  
					<li><a class="" href="<?php echo $this->url()?>">首页</a></li>
						<?php
			
							//文章分类列表
							$cats = Category::model()->getTree('_system_post');
							foreach($cats as $cat){
								if(!$cat['is_nav'])continue;
								echo '<li class="livea">', Html::link($cat['title'], array('cat/'.$cat['id']), array(
									'class'=>'nav-p',
									'title'=>false,
								));
								if(!empty($cat['children'])){
									echo '<div class="livs" style="display:none;"><ul>';
									foreach($cat['children'] as $c){
										if(!$c['is_nav'])continue;
										echo '<li>', Html::link($c['title'], array('cat/'.$c['id']), array(
											'title'=>false,
										)), '</li>';
									}
									echo '</ul></div>';
								}
							}
							echo '</li>';
						?>
		            <li><a class="" href="<?php echo $this->url('chat')?>">互动平台</a></li>
			    </ul>
			   </div>
			  </div>
	 	</div>
	 </div>
	 <script type="text/javascript">
	 	$(function(){
	 			$('.livea').hover(function(){
	 				$(this).find('.livs').slideDown('fast');
	 			},function(){
	 				$(this).find('.livs').slideUp('fast');
	 			})
	 	});
	 </script>

<!-- header end -->