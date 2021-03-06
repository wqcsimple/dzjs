<?php
use fayfox\helpers\Html;
use fayfox\models\tables\Props;
?>
<?php echo F::form()->inputHidden('refer')?>
<div class="form-field">
	<label class="title">属性名称<em class="color-red">*</em></label>
	<?php echo F::form()->inputText('title', array(
		'class'=>'full-width',
	))?>
</div>
<div class="form-field">
	<label class="title">属性别名</label>
	<?php echo F::form()->inputText('alias')?>
	<p class="description">特殊属性可能需要通过别名调用，可留空</p>
</div>
<div class="form-field">
	<label class="title">是否为必选属性</label>
	<?php echo F::form()->inputCheckbox('required', 1, array(
		'data-rule'=>'range',
		'data-params'=>"{range:['1']}",
		'data-label'=>'必须属性',
		'label'=>'必选',
	))?>
</div>
<div class="form-field">
	<label class="title">是否可见</label>
	<?php echo F::form()->inputCheckbox('is_show', 1, array(
		'data-rule'=>'range',
		'data-params'=>"{range:['1']}",
		'data-label'=>'可见性',
		'label'=>'可见',
	), true)?>
	<p class="description">在查看会员信息时，是否显示此属性</p>
</div>
<div class="form-field">
	<label class="title">排序值</label>
	<?php echo F::form()->inputText('sort', array(), 100)?>
</div>
<div class="form-field">
	<label class="title">类型</label>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_TEXT, array(
		'label'=>'输入框',
	), true)?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_RADIO, array(
		'label'=>'单选框',
	))?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_SELECT, array(
		'label'=>'下拉框',
	))?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_CHECKBOX, array(
		'label'=>'多选框',
	))?>
	<?php echo F::form()->inputRadio('element', Props::ELEMENT_TEXTAREA, array(
		'label'=>'文本域',
	))?>
</div>
<div class="form-field <?php if(empty($prop['element']) || !in_array($prop['element'], array(
	Props::ELEMENT_RADIO,
	Props::ELEMENT_SELECT,
	Props::ELEMENT_CHECKBOX,
))) echo 'hide';?>" id="prop-values-container">
	<label class="title">属性值</label>
	<?php echo F::form()->inputText('', array(
		'id'=>'prop-title',
	))?>
	<a href="javascript:;" class="btn-4" id="add-prop-value-link">添加</a>
	<span class="color-grey">（添加后可拖拽排序）</span>
	<div class="dragsort-list" id="prop-list">
	<?php if(isset($prop['values']) && is_array($prop['values'])){?>
		<?php foreach($prop['values'] as $pv){?>
			<div class="dragsort-item">
				<?php echo Html::inputHidden('ids[]', $pv['id'])?>
				<a class="dragsort-rm" href="javascript:;"></a>
				<a class="dragsort-item-selector"></a>
				<div class="dragsort-item-container">
					<?php echo F::form()->inputText("prop_values[]", array(
						'data-rule'=>'string',
						'data-params'=>'{max:255}',
						'data-label'=>'属性值',
						'data-required'=>'required',
					), $pv['title'])?>
				</div>
			</div>
		<?php }?>
	<?php }?>
	</div>
</div>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/fayfox.editsort.js"></script>
<script>
$(function(){
	$('#add-prop-value-link').on('click', function(){
		if($('#prop-title').val() == ''){
			alert('属性值不能为空！');
			return false;
		}
		$('#prop-list').append(['<div class="dragsort-item hide">',
			'<input type="hidden" name="ids[]" value="" />',
			'<a class="dragsort-rm" href="javascript:;"></a>',
			'<a class="dragsort-item-selector"></a>',
			'<div class="dragsort-item-container">',
				'<input type="text" name="prop_values[]" value="'+system.encode($("#prop-title").val())+'" data-label="属性值" data-rule="string" data-params="{max:255}" />',
			'</div>',
		'</div>'].join(''));
		$('#prop-list .dragsort-item:last').fadeIn();
		$('#prop-title').val('');
	});

	$('input[name="element"]').on('change', function(){
		if($(this).val() == <?php echo Props::ELEMENT_RADIO?> ||
			$(this).val() == <?php echo Props::ELEMENT_SELECT?> ||
			$(this).val() == <?php echo Props::ELEMENT_CHECKBOX?>){
			$('#prop-values-container').show();
		}else{
			$('#prop-values-container').hide();
		}
	});

	$('.edit-sort').feditsort({
		'url':system.url('admin/role-prop/sort')
	});
});
</script>