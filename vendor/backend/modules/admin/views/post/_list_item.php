<?php
use fayfox\helpers\Html;
use fayfox\models\Post;
use fayfox\helpers\Date;
use backend\helpers\PostHelper;
?>
<tr valign="top" id="post-<?php echo $data['id']?>">
	<td><?php echo Html::inputCheckbox('ids[]', $data['id'], false, array(
		'class'=>'batch-ids',
	));?></td>
	<td>
		<strong>
			<?php echo Html::link($data['title'] ? $data['title'] : '--无标题--', array('admin/post/edit', array(
				'id'=>$data['id'],
			)))?>
		</strong>
		<div class="row-actions">
		<?php if($data['deleted'] == 0){
			echo Html::link('编辑', array('admin/post/edit', array(
				'id'=>$data['id'],
			)), array(), true);
			echo Html::link('移入回收站', array('admin/post/delete', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'color-red',
			), true);
		}else{
			echo Html::link('还原', array('admin/post/undelete', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'undelete-post',
			), true);
			echo Html::link('永久删除', array('admin/post/remove', array(
				'id'=>$data['id'],
			)), array(
				'class'=>'delete-post color-red remove-link',
			), true);
		}?>
		</div>
	</td>
	<?php if(in_array('main_category', $cols)){?>
	<td class="wp15"><?php echo Html::link($data['cat_title'], array('admin/post/index', array(
		'cat_id'=>$data['cat_id'],
	)));?></td>
	<?php }?>
	<?php if(in_array('category', $cols)){?>
	<td><?php
		$cats = Post::model()->getCats($data['id']);
		foreach($cats as $key => $cat){
			if($key){
				echo ', ';
			}
			echo Html::link($cat['title'], array('admin/post/index', array(
				'cat_id'=>$cat['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(in_array('tags', $cols)){?>
	<td><?php
		$tags = Post::model()->getTags($data['id']);
		foreach($tags as $key => $tag){
			if($key){
				echo ', ';
			}
			echo Html::link($tag['title'], array('admin/post/index', array(
				'tag_id'=>$tag['id'],
			)));
		}
	?></td>
	<?php }?>
	<?php if(in_array('status', $cols)){?>
	<td class="wp10">
	<?php echo PostHelper::getStatus($data['status'], $data['deleted']);?>
	</td>
	<?php }?>
	<?php if(in_array('user', $cols)){?>
	<td><?php echo Html::encode($data[F::form('setting')->getData('display_name', 'username')])?></td>
	<?php }?>
	<?php if(in_array('views', $cols)){?>
	<td><?php echo $data['views']?></td>
	<?php }?>
	<?php if(in_array('comments', $cols)){?>
	<td><?php echo $data['comments']?></td>
	<?php }?>
	<?php if(in_array('publish_time', $cols)){?>
	<td class="col-date">
		<span class="time abbr" title="<?php echo Date::format($data['publish_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['publish_time']);
			}else{
				echo Date::format($data['publish_time']);
			}?>
		</span>
	</td>
	<?php }?>
	<?php if(in_array('last_view_time', $cols)){?>
	<td class="col-date">
		<span class="time abbr" title="<?php echo Date::format($data['last_view_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['last_view_time']);
			}else{
				echo Date::format($data['last_view_time']);
			}?>
		</span>
	</td>
	<?php }?>
	<?php if(in_array('last_modified_time', $cols)){?>
	<td class="col-date">
		<span class="time abbr" title="<?php echo Date::format($data['last_modified_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['last_modified_time']);
			}else{
				echo Date::format($data['last_modified_time']);
			}?>
		</span>
	</td>
	<?php }?>
	<?php if(in_array('create_time', $cols)){?>
	<td class="col-date">
		<span class="time abbr" title="<?php echo Date::format($data['create_time'])?>">
			<?php if(F::form('setting')->getData('display_time', 'short') == 'short'){
				echo Date::niceShort($data['create_time']);
			}else{
				echo Date::format($data['create_time']);
			}?>
		</span>
	</td>
	<?php }?>
	<?php if(in_array('sort', $cols)){?>
	<td><?php echo Html::inputText("sort[{$data['id']}]", $data['sort'], array(
		'size'=>3,
		'maxlength'=>3,
		'data-id'=>$data['id'],
		'class'=>'post-sort w30',
	))?></td>
	<?php }?>
</tr>