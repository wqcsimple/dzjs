<?php
use fayfox\helpers\Html;
use fayfox\helpers\String;
use fayfox\helpers\Date;
?>
		<div class="zlxz">
			<div class="bt"><span><i class="icon-tags"></i><?php echo Html::encode($data['title'])?></span><p><?php
			echo Html::link('more', array('cat/'.$data['top']),array('class'=>'icon-angle-double-right'));?></p></div>
			<ul>
			<?php foreach ($posts as $p){ ?>
		       <li>
                          <?php
		    		if(!empty($data['date_format'])){
					echo '<span>'.Date::niceShort($p['publish_time']).'</span>';
				}
		       		echo Html::link(String::niceShort($p['title'],17), array(str_replace('{$id}', $p['id'], $data['uri'])));
                          ?>
		       </li>
		       
		        <?php }?>
            </ul>


		</div>