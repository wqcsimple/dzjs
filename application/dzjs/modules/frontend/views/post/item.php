<?php 
use fayfox\helpers\Html;
use fayfox\helpers\Date;
use fayfox\models\File;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->staticFile('css/fuye.css')?>">
 <div class="alltop"><?php F::app()->widget->load('post-banner-image')?></div>
 <div class="aio">

 <div class="gyleft">

<?php F::app()->widget->load('hot-news')?>


<?php F::app()->widget->load('list-zlxz')?>
	


  
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
								echo '附件：';
								foreach($content['files'] as $f){
									echo Html::link($f['description'], array('file/download', array(
										'id'=>$f['file_id'],
									)));
								}
							}
						?>
		  </div>
		</div>
  </div>
  </div>
 </div>

<!----------------附页公用右侧---------------------->