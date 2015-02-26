<?php
use fayfox\models\Option;
?>
<div class="site-nav">
	<div class="w1000">
		<div class="fl">
			<a onclick="homePage(this)" href="#">设为首页</a>
			<span class="dp">|</span>
			<a href="javascript:;" onclick="AddFavorite('<?php echo $this->url()?>', '<?php echo Option::get('sitename')?>')">加入收藏</a>
			<span class="dp">|</span>
			<a href="">联系我们</a>
		</div>
		<div class="fr">
			<h5>@ 邮箱登录</h5>
			<span class="dp-blue">&nbsp;</span>
			<form action="http://mail.cddx.gov.cn:8080/login.html" method="post">
				<fieldset>
					<label>用户名</label>
					<input type="text" name="username" />
				</fieldset>
				<fieldset>
					<label>密码</label>
					<input type="password" name="password" />
				</fieldset>
				<fieldset>
					<a href="javascript:;" id="email-form-submit">登录</a>
				</fieldset>
			</form>
		</div>
	</div>
</div>
<script>
function AddFavorite(sURL, sTitle){
	try{
		window.external.addFavorite(sURL, sTitle);
	}catch (e){
		try{
			window.sidebar.addPanel(sTitle, sURL, "");
		}catch (e){
			alert("你的浏览器不支持加入收藏功能，请快捷键来CTRL+D来加入<?php echo Option::get('sitename')?>");
		}
	}
};
$('#email-form-submit').on('click', function(){
	$('#email-form').submit();
});
</script>