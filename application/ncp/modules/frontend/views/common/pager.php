<?php
use fayfox\helpers\Html;
use ncp\helpers\FriendlyLink;
if($listview->totalPages > 1){

isset($params) || $params = array();
?>
<div class="page1">
	<?php
	echo Html::link('首页', FriendlyLink::getLink($type, array(
		'page'=>1
	) + $params), array(
		'class'=>'pg1',
		'title'=>'首页',
		'encode'=>false,
	));
	//上一页
	if($listview->currentPage == 2){
		echo Html::link('上一页', FriendlyLink::getLink($type, array(
			'page'=>1
		) + $params), array(
			'class'=>'prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}else if($listview->currentPage > 2){
		echo Html::link('上页', FriendlyLink::getLink($type, array(
			'page'=>$listview->currentPage - 1
		) + $params), array(
			'class'=>'prev',
			'title'=>'上一页',
			'encode'=>false,
		));
	}
	
	//首页
	if($listview->currentPage > ($listview->adjacents + 1)) {
		echo Html::link(1, FriendlyLink::getLink($type, array(
			'page'=>1
		) + $params), array(
			'class'=>'num',
		));
	}
	
	//点点点
	if($listview->currentPage > ($listview->adjacents + 2)) {
		echo '<span class="num dots">&hellip;</span>';
	}
	
	//页码
	$pmin = $listview->currentPage > $listview->adjacents ? $listview->currentPage - $listview->adjacents : 1;
	$pmax = $listview->currentPage < $listview->totalPages - $listview->adjacents ? $listview->currentPage + $listview->adjacents : $listview->totalPages;
	for($i=$pmin; $i<=$pmax; $i++){
		if($i == $listview->currentPage){
			echo '<span class="num1">', $i, '</span>';
		}else if($i == 1){
			echo Html::link(1, FriendlyLink::getLink($type, array(
				'page'=>1
			) + $params), array(
				'class'=>'num',
			));
		}else{
			echo Html::link($i, FriendlyLink::getLink($type, array(
				'page'=>$i
			) + $params), array(
				'class'=>'num',
			));
		}
	}
	
	//点点点
	// interval
	if($listview->currentPage < ($listview->totalPages - $listview->adjacents - 1)) {
		echo '<span class="num dots">&hellip;</span>';
	}
	
	//尾页
	if($listview->currentPage < $listview->totalPages - $listview->adjacents) {
		echo Html::link($listview->totalPages, FriendlyLink::getLink($type, array(
			'page'=>$listview->totalPages
		) + $params), array(
			'class'=>'num',
		));
	}
	
	//下一页
	if($listview->currentPage < $listview->totalPages){
		echo Html::link('下一页', FriendlyLink::getLink($type, array(
			'page'=>$listview->currentPage + 1
		) + $params), array(
			'class'=>'next',
			'title'=>'下一页',
			'encode'=>false,
		));
	}
	echo Html::link('尾页', FriendlyLink::getLink($type, array(
		'page'=>$listview->totalPages
	) + $params), array(
		'class'=>'pg1',
		'title'=>'尾页',
		'encode'=>false,
	));
	?>
</div>
<?php }?>