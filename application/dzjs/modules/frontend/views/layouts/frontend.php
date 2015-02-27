<?php 
use fayfox\models\Option;
use fayfox\helpers\Html;
?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title><?php if(!empty($title)){
	echo $title, ' - ';
}
echo Option::get('sitename')?></title>
	<meta name="keywords" content="<?php 
									if($keywords !== ''){
										echo Html::encode($keywords);
									}else{
										echo Option::get('seo_index_keywords');
									}?>" />
	<meta name="description" content="<?php 
									if($keywords !== ''){
										echo Html::encode($description);
									}else{
										echo Option::get('seo_index_description');
									}?>" />
      <link type="image/x-icon" href="<?php echo $this->url()?>favicon.ico" rel="shortcut icon" />
	<link rel="stylesheet" type="text/css" href="<?php echo $this->staticFile('css/common.css')?>">
	<link rel="stylesheet" href="<?php echo $this->staticFile('css/fontello.css')?>" media="all" />

	<script type="text/javascript" src="<?php echo $this->staticFile('js/jquery.js')?>"></script>
	<script type="text/javascript" src="<?php echo $this->url()?>js/custom/system.min.js"></script>
    <script>
        system.base_url = '<?php echo $this->url()?>';
        system.user_id = '<?php echo F::app()->session->get('id', 0)?>';
    </script>
<?php echo $this->getCss()?>
</head>
<body>
	<?php include '_header.php'; ?>
	<?php echo $content ?>
	<?php include '_footer.php'; ?>


</body>
</html>