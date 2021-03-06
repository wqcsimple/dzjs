<?php
use fayfox\models\tables\ExamQuestions;
use fayfox\helpers\Html;
use fayfox\models\Exam;
?>
<form id="form" method="post" class="validform">
	<div class="col-2-2">
		<div class="col-2-2-body-sidebar" id="side">
			<div class="box" id="box-operation">
				<div class="box-title">
					<h4>操作</h4>
				</div>
				<div class="box-content">
					<div>
						<a href="javascript:;" class="btn-1" id="form-submit">编辑</a>
					</div>
					<div class="misc-pub-section">
						<strong>状态</strong>
						<?php echo F::form()->inputRadio('status', ExamQuestions::STATUS_ENABLED, array('label'=>'启用'), true)?>
						<?php echo F::form()->inputRadio('status', ExamQuestions::STATUS_DISABLED, array('label'=>'禁用'))?>
					</div>
				</div>
			</div>
			<?php $this->renderPartial('_box_type')?>
			<?php $this->renderPartial('_box_category')?>
			<?php $this->renderPartial('_box_score')?>
			<?php $this->renderPartial('_box_sort')?>
		</div>
		<div class="col-2-2-body">
			<div class="col-2-2-body-content">
				<?php $this->renderPartial('_box_question')?>
				<div class="box" id="box-answers">
					<div class="box-title">
						<h4>答案</h4>
					</div>
					<div class="box-content" id="answer-container">
						<div id="selector-panel" <?php if(isset($question['type']) && $question['type'] != ExamQuestions::TYPE_SINGLE_ANSWER && $question['type'] != ExamQuestions::TYPE_MULTIPLE_ANSWERS)echo 'class="hide"';?>>
							<a href="javascript:;" id="create-answer-link" class="btn-1">添加答案</a>
							<label>
								<?php echo F::form()->inputCheckbox('rand', 1)?>
								随机排序
							</label>
							( 钩选此项，试题将随机排序 )
							<div class="dragsort-list answer-list">
							<?php foreach($answers as $a){?>
								<div class="dragsort-item">
									<?php if(!Exam::isAnswerExamed($a['id'])){
										echo Html::link('', 'javascript:;', array(
											'class'=>'dragsort-rm',
										));
									}?>
									<a class="dragsort-item-selector"></a>
									<div class="dragsort-item-container mr10">
									<?php
										echo Html::textarea("selector_answers[{$a['id']}]", $a['answer'], array(
											'class'=>'full-width autosize',
										));
										if(F::form()->getData('type') == ExamQuestions::TYPE_MULTIPLE_ANSWERS){
											echo Html::inputCheckbox('selector_right_answers[]', $a['id'], $a['is_right_answer'] ? true : false, array(
												'label'=>'正确答案',
											));
										}else{
											echo Html::inputRadio('selector_right_answers[]', $a['id'], $a['is_right_answer'] ? true : false, array(
												'label'=>'正确答案',
											));
										}
									?>
									</div>
								</div>
							<?php }?>
							</div>
						</div>
						<div id="input-panel" <?php if(!isset($question['type']) || $question['type'] != ExamQuestions::TYPE_INPUT)echo 'class="hide"';?>>
						<?php echo Html::textarea('input_answer', !empty($answers[0]['answer']) ? $answers[0]['answer'] : '', array(
							'class'=>'full-width h90 autosize',
						))?>
						</div>
						<div id="true-or-false-panel" <?php if(!isset($question['type']) || $question['type'] != ExamQuestions::TYPE_TRUE_OR_FALSE)echo 'class="hide"';?>>
						<?php
							echo Html::inputRadio('true_or_false_answer', 1, !empty($answers[0]['is_right_answer']) ? true : false, array(
								'label'=>'正确',
							));
							echo Html::inputRadio('true_or_false_answer', 0, empty($answers[0]['is_right_answer']) ? true : false, array(
								'label'=>'错误',
							));
						?>
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript" src="<?php echo $this->url()?>js/custom/admin/question.js"></script>
<script>
common.filebrowserImageUploadUrl = system.url("admin/file/upload", {'t':'exam'});
question.type = {
	'true_or_false':<?php echo ExamQuestions::TYPE_TRUE_OR_FALSE?>,
	'single_answer':<?php echo ExamQuestions::TYPE_SINGLE_ANSWER?>,
	'input':<?php echo ExamQuestions::TYPE_INPUT?>,
	'multiple_answers':<?php echo ExamQuestions::TYPE_MULTIPLE_ANSWERS?>
};
$(function(){
	question.init();
});
</script>