<?php 
use fayfox\helpers\Html;
use fayfox\helpers\Date;
use fayfox\models\File;
use fayfox\models\tables\Files;

?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->staticFile('css/fuye.css')?>">
 <div class="alltop"><?php F::app()->widget->load('post-banner-image')?></div>
 <div class="aio">

 <div class="gyleft">

<?php F::app()->widget->load('hot-news')?>


<?php F::app()->widget->load('list-zlxz')?>
	
</div>

  
<!----------------附页公用左侧---------------------->
 <div class="gyright">
  <div class="gyright_head"><a href="<?php echo $this->url()?>">首页</a>
  	  		><a href="<?php echo $this->url('cat/'.$content['cat_id'])?>" ><?php echo $content['cat_title']?></a>
	  		><a href="" ><?php echo $content['title']?></a>
  </div>
  <div class="zwd">
	  <div class="zw">
			<h2><?php echo Html::encode($content['title'])?></h2>
			<h3> 时间：<?php echo Html::encode(Date::format($content['publish_time']))?>&nbsp;&nbsp;作者：admin&nbsp;&nbsp;阅读数：<?php echo $content['views']?></h3>
			<div class="content">
				<?php echo $content['content']?>
				 <div class="download">
				 <?php

								if(!empty($content['files'])){
									echo "附件：";
									foreach($content['files'] as $k => $f){
										$k++;
										echo "<div class='files'>".$k.".<i class='icon-attach'></i>";
										echo Html::link($f['description'], array('file/download', array(
											'id'=>$f['file_id'],
											'name'=>'date',
										)));
										echo "<span> (下载次数:".File::model()->getDownloads($f['file_id']).")</span></div>";
									}
								}
							?>
				</div>
			  </div>
			</div>
			<div class="post-nav">
								<p>上一篇：<?php if($content['nav']['prev']){
									echo Html::link($content['nav']['prev']['title'], array(
										'post/'.$content['nav']['prev']['id'],
									));
								}else{
									echo '没有了';
								}?></p>
								<p>下一篇：<?php if($content['nav']['next']){
									echo Html::link($content['nav']['next']['title'], array(
										'post/'.$content['nav']['next']['id'],
									));
								}else{
									echo '没有了';
								}?></p>
			</div>
	  </div>
  </div>
 </div>

<!----------------附页公用右侧---------------------->