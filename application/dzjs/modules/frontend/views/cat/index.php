<?php 
use fayfox\helpers\Date;

?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->staticFile('css/fuye.css')?>">
 <div class="alltop"><?php F::app()->widget->load('list-banner-image')?></div>
 <div class="aio">

 <div class="gyleft">


<?php F::app()->widget->load('hot-news')?>
<?php F::app()->widget->load('list-zlxz')?>
  
<!----------------附页公用左侧----------------------> 

 <div class="gyright">

   
   <div class="gyright_head1"><a href="<?php echo $this->url()?>">首页</a>><a href="<?php echo $this->url('cat/'.$cat['id'])?>" ><?php echo $cat['title']?></a>

  </div>

  
               
   
   <ul class="tzgg">

	<?php $listview->showData();?>
   </ul>

	<?php $listview->showPage();?>
    
  </div>
 
<!----------------附页公用右侧----------------------> 