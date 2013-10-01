<?php namespace Habari; ?>
<!doctype html>
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>Haunted &raquo;<?php echo $theme->title; ?></title>
	<meta name="description" content="">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0"> 
	<?php Haunted::css('style'); ?>
	<?php Haunted::css('css/fontawesome.min'); ?>
	<script src="//code.jquery.com/jquery-latest.min.js"></script>
	<?php Haunted::js('jquery.tagsinput'); ?>
	<?php Haunted::js('dropzone'); ?>
	<script type="text/javascript">
	if ( typeof(ADMIN) == "undefined" ) { ADMIN = {}; }
		ADMIN.url = "<?php Site::out_url('habari'); ?>";
	</script>
</head>
<body>
	<div id="site-container" class="grid wfull">
