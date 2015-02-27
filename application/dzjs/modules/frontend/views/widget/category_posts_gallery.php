<?php 
use fayfox\helpers\Html;
use fayfox\models\File;
use fayfox\helpers\String;
?>
<div class="bt"><span><i class="icon-picture"></i>&nbsp;<?php echo Html::encode($data['title'])?></span><p><?php
			echo Html::link('more', array('cat/'.$data['top']),array('class'=>'icon-angle-double-right'));?></p></div>
<div class="bd" id="bd">
<div class="wrap">
    <ul>
        <?php foreach ($posts as $p){?>
    
        <li>
            <div class="pic">
                <?php echo Html::link(Html::img($p['thumbnail'],File::PIC_ZOOM, array(
                    'dw' => 184,
                    'dh'=> 132,
                )), array(str_replace('{$id}', $p['id'], $data['uri'])), array(
                    'encode' => false,
                    'alt'    => $p['title'],
                    'title'  => $p['title'],
                ))?>
              
            </div>
            <div class="title">
                <?php echo Html::link(String::niceShort($p['title'], 14), array(str_replace('{$id}', $p['id'], $data['uri'])))?>
            </div>
        </li>
     <?php }?>
    </ul>
</div>
</div>
