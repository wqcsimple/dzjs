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
        <div class="container">
        
            <div class="row" >
            <form id = "message">
               <blockquote>
                       <h6>我要留言：</h6>
                       <?php echo Html::inputHidden('parent', 0)?>
                       <textarea name="content" id="content" cols="30" rows="10"></textarea>
                       <h6>您的姓名：</h6><input type="text" name="realname" id="name" />
                       <button class="button-primary" id="sub-message" />发布留言</button><img id="loading" src="<?php echo $this->staticFile('images/loading.gif')?>" style="margin-left:20px;float:right;display:none;"/>
               </blockquote>
               </form>
            </div>
         
        </div>
        
       <div class="container">
            <ul class="message-list">
               <?php $listview->showData()?>
                
            </ul>
            
            
       </div>
       <?php $listview->showPage();?>
       

    

</div>

<script>
  $(document).on('click', '#sub-message', function(e){
	  e.preventDefault();
	  $('#loading').show();
      $.ajax({
        url: system.url('chat/create'),
        type: 'post',
        dataType: 'json',
        data: $('#message').serialize(),
        success: function(data){
          if (data.status) {
            alert(data.message);
            window.location.reload();
          }else{
            alert(data.message);
          }
        }
      });
    });


</script>