<?php
use fayfox\helpers\Html;
use fayfox\helpers\Date;
use fayfox\models\tables\ExamPapers;
use fayfox\helpers\String;
?>
<tr valign="top">
	<td>
		<strong><?php echo Html::encode($data['title'])?></strong>
		<div class="row-actions">
			<?php 
				echo Html::link('编辑', array('admin/exam-paper/edit', array(
					'id'=>$data['id'],
				)), array(), true);
				echo Html::link('删除', array('admin/exam-paper/delete', array(
					'id'=>$data['id'],
				)), array(
					'class'=>'color-red remove-link',
				), true);
			?>
		</div>
	</td>
	<td><?php echo Html::encode($data['cat_title']);?></td>
	<td><?php switch($data['status']){
		case ExamPapers::STATUS_ENABLED:
			echo '<span class="color-green">启用</span>';
			break;
		case ExamPapers::STATUS_DISABLED:
			echo '<span class="color-red">禁用</span>';
			break;
	}?></td>
	<td><?php echo String::money($data['score'])?></td>
	<td><span class="time abbr" title="<?php echo Date::format($data['create_time'])?>">
		<?php echo Date::niceShort($data['create_time'])?>
	</span></td>
	<td><span class="time abbr" title="<?php echo Date::format($data['last_modified_time'])?>">
		<?php echo Date::niceShort($data['last_modified_time'])?>
	</span></td>
</tr>