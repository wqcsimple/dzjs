<?php
use fayfox\models\Option;
?>
<?php $this->renderPartial('common/_friendlinks')?>
<footer class="g-ft">
	<div class="w1000">
		<p class="ft-cp"><?php echo Option::get('copyright')?></p>
		<p>
			主办：<?php echo Option::get('organizers')?>
			地址：<?php echo Option::get('address')?>
			邮编：<?php echo Option::get('postcode')?>
		</p>
		<p>[<?php echo Option::get('beian')?>] 技术支持：<a href="http://www.fayfox.com">Fayfox</a></p>
	</div>
</footer>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/analyst-min.js"></script>
<script>_fa.init();</script>