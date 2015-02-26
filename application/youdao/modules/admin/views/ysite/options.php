<form id="form" action="" method="post" class="validform">
	<div class="form-field">
		<label class="title">站点名称</label>
		<?php echo Html::inputText('sitename', Option::get('sitename'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">版权信息</label>
		<?php echo Html::inputText('copyright', Option::get('copyright'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">电话</label>
		<?php echo Html::inputText('youdao_phone', Option::get('youdao_phone'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">传真</label>
		<?php echo Html::inputText('youdao_fax', Option::get('youdao_fax'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<label class="title">电子邮箱</label>
		<?php echo Html::inputText('youdao_email', Option::get('youdao_email'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
			'datatype'=>'e',
		))?>
	</div>
	<div class="form-field">
		<label class="title">公司地址</label>
		<?php echo Html::inputText('youdao_address', Option::get('youdao_address'), array(
			'class'=>'w300',
			'ignore'=>'ignore',
		))?>
	</div>
	<div class="form-field">
		<a href="javascript:;" class="btn-1" id="form-submit">提交保存</a>
	</div>
</form>