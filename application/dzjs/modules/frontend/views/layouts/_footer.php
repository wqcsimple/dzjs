<?php 
use fayfox\models\Option;
?>
<!-- footer -->
<div class="foot" style=" clear:both">
    <div class="footin">
        <h1><img src="<?php echo $this->staticFile('images/footerlogo.png') ?>"></h1>
        <div class="fp">
            <p><?php echo Option::get('phone')?> <a href="" style="color:#313131">更多联系方式</a></p>
            <p><?php echo Option::get('copyright')?> </p>
            <p>今日访问量：0 总访问量：11822</p>
        </div>

        <div class='send'>
                <a href="" target="_blank"  class='weixin'></a>
                <a href="javascript:;" class="blog"></a>
                <p><img src="<?php echo $this->staticFile('images/weixin2.png')?>" alt=""></p>
            </div>
    </div>
</div>
<script>
$(function(){

	$('.blog').hover(function() {
		$('.send p').show();
	}, function() {
		$('.send p').hide();
	});
	

});
</script>
