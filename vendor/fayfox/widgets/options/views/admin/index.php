<?php
use fayfox\helpers\Html;
?>
<style>
#widget-attr-list li{background:url("<?php echo $this->url()?>css/admin/images/move.png") no-repeat scroll 3px center #FFFFFF;padding-left:25px;border-left:2px solid #35AA47;margin-bottom:5px;}
.widget-attr-list li{height:66px;}
.widget-attr-list li .golden-left,.widget-attr-list li .golden-right{display:block;padding:5px 1% 0 0;}
.widget-attr-list li .golden-left{width:38%;float:left;}
.widget-attr-list li .golden-right{margin-left:40%;}
#widget-attr-list .widget-attr-list-header{background:none;border-left:0 none;}
.widget-attr-list-header{height:auto !important;}
.widget-attr-list-header span{text-align:center;font-weight:bold;}
.place-holder{border:2px dashed #DFDFDF !important;}
</style>
<div class="box">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>属性集</h4>
	</div>
	<div class="box-content">
		<ul id="widget-attr-list" class="widget-attr-list">
			<li class="widget-attr-list-header">
				<span class="golden-left">名称</span>
				<span class="golden-right">值</span>
			</li>
			<?php 
			if(isset($data['data'])){
				foreach($data['data'] as $d){?>
			<li>
				<span class="golden-left">
					<?php echo Html::inputText('keys[]', $d['key'], array(
						'class'=>'full-width',
					))?>
					<?php echo Html::link('删除', 'javascript:;', array(
						'class'=>'btn-2 mt5 widget-remove-attr-link',
					))?>
				</span>
				<span class="golden-right"><?php echo Html::textarea('values[]', $d['value'], array(
					'class'=>'full-width',
				))?></span>
			</li>
				<?php }
			}?>
		</ul>
	</div>
</div>
<div class="box">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>添加属性</h4>
	</div>
	<div class="box-content">
		<ul class="widget-attr-list">
			<li class="widget-attr-list-header">
				<span class="golden-left">名称</span>
				<span class="golden-right">值</span>
			</li>
			<li>
				<span class="golden-left">
					<?php echo Html::inputText('', '', array(
						'class'=>'full-width',
						'id'=>'widget-add-attr-key',
					))?>
					<?php echo Html::link('添加', 'javascript:;', array(
						'class'=>'btn-1 mt5',
						'id'=>'widget-add-attr-link',
					))?>
					<span id="widget-add-attr-msg"></span>
				</span>
				<span class="golden-right"><?php echo Html::textarea('', '', array(
					'class'=>'full-width',
					'id'=>'widget-add-attr-value',
				))?></span>
			</li>
		</ul>
	</div>
</div>
<div class="box">
	<div class="box-title">
		<a class="tools toggle" title="点击以切换"></a>
		<h4>渲染模板</h4>
	</div>
	<div class="box-content">
		<?php echo Html::textarea('template', isset($data['template']) ? $data['template'] : '', array(
			'class'=>'full-width',
		))?>
		<p class="color-grey">
			<span class="color-orange">{$key}</span>代表“名称”，
			<span class="color-orange">{$value}</span>代表“值”。
			例如：<?php echo Html::encode('<p><label>{$key}</label>{$value}</p>')?>
		</p>
	</div>
</div>
<script>
var widget_options = {
	'addAttr':function(){
		$(document).delegate('#widget-add-attr-link', 'click', function(){
			if($("#widget-add-attr-key").val() == ""){
				$("#widget-add-attr-msg").css({"color":"red"}).text("名称不能为空");
			}else{
				$("#widget-add-attr-msg").css({"color":""}).text("");
				var html = [
					'<li>',
						'<span class="golden-left">',
							'<input type="text" name="keys[]" value="', $("#widget-add-attr-key").val(), '" class="full-width" />',
							'<br />',
							'<a href="javascript:;" class="btn-2 mt5 remove-link widget-remove-attr-link">删除</a>',
						'</span>',
						'<span class="golden-right">',
							'<textarea name="values[]" class="full-width">', $("#widget-add-attr-value").val(), '</textarea>',
						'</span>',
					'</li>'
				].join('');
				$("#widget-attr-list").append(html);
				$("#widget-attr-list").dragsort("destroy");
				$("#widget-attr-list").dragsort({
					'dragSelectorExclude': 'input,textarea,.widget-attr-list-header',
					'placeHolderTemplate': '<li class="place-holder"></li>'
				});
				$("#widget-add-attr-key").val('');
				$("#widget-add-attr-value").val('')
			}
		});
	},
	'removeAttr':function(){
		$(document).delegate('.widget-remove-attr-link', 'click', function(){
			if(confirm("您确定要删除此属性吗？")){
				$(this).parent().parent().remove();
			}
		});
	},
	'dragsort':function(){
		system.getScript(system.url('js/jquery.dragsort-0.5.1.js'), function(){
			$("#widget-attr-list").dragsort({
				'dragSelectorExclude': 'input,textarea,.widget-attr-list-header',
				'placeHolderTemplate': '<li class="place-holder"></li>'
			});
		});
	},
	'init':function(){
		this.addAttr();
		this.removeAttr();
		this.dragsort();
	}
};
$(function(){
	widget_options.init();
});
</script>