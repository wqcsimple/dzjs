<?php 
use fayfox\helpers\Html;
use fayfox\helpers\Date;
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
  	  ><a href="" >教学队伍介绍</a>
  </div>
  <div class="zwd">
      <div class="zw">
       <h2><?php echo Html::encode($about['title'])?></h2>
          <?php echo $about['content']?>
      </div>
  </div>
 </div>

<!----------------附页公用右侧---------------------->