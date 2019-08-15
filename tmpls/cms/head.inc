<?php
	$this->add_style(array (
		'http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800&amp;subset=latin,cyrillic',
		'http://fonts.googleapis.com/css?family=Noto+Serif:400,700,400italic,700italic&amp;subset=latin,cyrillic',
		'/js/tools/cleditor/jquery.cleditor.css',
		'/js/tools/colpick/colpick.min.css',
		'/js/tools/cms/cms.css',
	));
	$this->add_js(array (
		'/js/tools/jquery.min.js',
		'/js/tools/jquery-ui/jquery-ui.min.js',
		'/js/tools/cleditor/jquery.cleditor.min.js',
		'/js/tools/colpick/colpick.min.js',
		'//api-maps.yandex.ru/2.1/?lang=ru_RU',
		'/js/tools/cms/cms.js',
	));
?>
<!-- HEAD SECTION START -->
		<title><?= $current->title; ?></title>
		<meta name="description" content="<?= $current->desc; ?>">
		<meta name="keywords" content="<?= $current->key; ?>">
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<meta name="viewport" content="width=device-width">
		<meta name="format-detection" content="telephone=no">
		<meta http-equiv="x-rim-auto-match" content="none">

		<meta property="og:type" content="article">
		<meta property="og:site_name" content="<?= $pages->main->title; ?>">
		<meta property="og:title" content="<?= $current->title; ?>">
		<meta property="og:url" content="<?= $current->title; ?>">
		<? if (isset($current->img) && !empty($current->img->url)): ?>
			<meta property="og:image" content="<?= $current->img->url; ?>">
			<meta property="og:image:width" content="<?= $current->img->width; ?>">
			<meta property="og:image:height" content="<?= $current->img->height; ?>">
		<? endif; ?>
		<meta property="og:description" content="<?= $pages->main->desc; ?>">

		<script type="text/javascript" src="/js/tools/fixes/console-fix.js"></script>
		<!--[if lt IE 9]>
			<script type="text/javascript" src="/js/tools/fixes/html5.js"></script>
			<script type="text/javascript" src="/js/tools/fixes/ie9.js"></script>
			<script type="text/javascript" src="/js/tools/fixes/css3-mediaqueries.js"></script>
		<![endif]-->
		<link href="/favicon.ico" rel="icon" type="image/x-icon">
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
		<?= $this->include_style(); ?>

<!-- /HEAD SECTION END -->
