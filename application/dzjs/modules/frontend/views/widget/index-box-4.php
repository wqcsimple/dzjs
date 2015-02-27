<?php
use fayfox\helpers\Html;
use fayfox\helpers\String;
?>
		<div class="xsdt">
			<div class="bt"><span><i class="icon-tags"></i><?php
			echo Html::link(Html::encode($data['title']), array('cat/'.$data['top']));?></div>
			<ul>
			<?php foreach ($posts as $p){ ?>
		       <li>
                          <?php
		    		
		       		echo Html::link(String::niceShort($p['title'],15), array(str_replace('{$id}', $p['id'], $data['uri'])));
                          ?>
		       </li>
		       
		        <?php }?>
            </ul>


		</div>