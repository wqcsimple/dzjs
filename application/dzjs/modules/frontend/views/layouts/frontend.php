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
<?php echo $this->getCss()?>
	<script type="text/javascript" src="<?php echo $this->staticFile('js/jquery.js')?>"></script>

</head>
<body>
	<?php include '_header.php'; ?>
	<?php echo $content ?>
	<?php include '_footer.php'; ?>

	</script> <script type="text/javascript" src="<?php echo $this->staticFile('js/index.js'); ?>"></script>
</body>
</html>