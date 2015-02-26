<?php 
use fayfox\helpers\Html;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $this->staticFile('css/fuye.css')?>">
 <div class="alltop"><?php F::app()->widget->load('post-banner-image')?></div>
  <div class="aio">

 <div class="gyleft">

<?php F::app()->widget->load('hot-news')?>


<?php F::app()->widget->load('list-zlxz')?>

<!----------------附页公用左侧----------------------> 

<div class="gyright">
    <div class="gyright_head1"><a href="<?php echo $this->url()?>">首页</a>>
        <a href="">互动平台</a></div>
        <form action="" role="form" >
            <div class="form-group">
                <div class="lyh"><h1>| 我要留言</h1></div>
                <textarea name="content" id="content" cols="30" rows="5" class="form-control"></textarea>
            </div>
            <div class="form-group">
                <span class="lyh">您的姓名</span>
                <input type="text" name="realname" id="realname"  />
            </div>
        </form>
    

</div>