<?php
	$this->add_style(array (
		'https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300,700|Open+Sans:300,400,600,700,800&display=swap',
		'/js/tools/colorbox/style/colorbox.css',
		'/js/tools/owl-carousel/owl.carousel.css',
		//'/js/tools/wow/animate.css',
		'/styles/style.css',
		'/local/templates/r-promstroy/assets/app.css'
		//'/styles/adaptive-1200.css',
		//'/styles/adaptive-992.css',
		//'/styles/adaptive-768.css',
		//'/styles/adaptive-480.css',
	));
	$this->add_js(array (
		'/js/tools/jquery.min.js',
		'/js/tools/colorbox/jquery.colorbox-min.js',
		'/js/tools/owl-carousel/owl.carousel.min.js',
		//'//vk.com/js/api/openapi.js?151',
		//'/js/tools/wow/wow.min.js',
		'//api-maps.yandex.ru/2.1/?lang=ru_RU',
		'/js/tools/core/core.js',
		'/js/scripts.js',
        '/local/templates/r-promstroy/assets/app.js'
	));
?>
<!-- HEAD SECTION START -->
		<title><?= $current->title; ?></title>
		<meta name="description" content="<?= $current->desc; ?>">
		<meta name="keywords" content="<?= $current->key; ?>">
		<meta charset="utf-8">
		<meta http-equiv="content-type" content="text/html; charset=utf-8">
		<?/*<meta name="viewport" content="width=device-width">*/?>
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="format-detection" content="telephone=no">
		<meta http-equiv="x-rim-auto-match" content="none">

		<meta property="og:type" content="article">
		<meta property="og:site_name" content="<?= $current->title; ?>">
		<meta property="og:title" content="<?= $current->title; ?>">
		<meta property="og:description" content="<?= $current->desc; ?>">
		<meta property="og:url" content="<?= $current->url; ?>">
		<? if (isset($current->img) && !empty($current->img->url)): ?>
			<meta property="og:image" content="<?= $current->img->url; ?>">
			<meta property="og:image:width" content="<?= $current->img->width; ?>">
			<meta property="og:image:height" content="<?= $current->img->height; ?>">
		<? endif; ?>

		<script type="text/javascript" src="/js/tools/fixes/console-fix.js"></script>
		<!--[if lt IE 9]>
			<script type="text/javascript" src="/js/tools/fixes/html5.js"></script>
			<script type="text/javascript" src="/js/tools/fixes/ie9.js"></script>
			<script type="text/javascript" src="/js/tools/fixes/css3-mediaqueries.js"></script>
		<![endif]-->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/vue/2.6.10/vue.min.js"></script>
		<link href="/favicon.ico" rel="icon" type="image/x-icon">
		<link href="/favicon.ico" rel="shortcut icon" type="image/x-icon">
		<?= $this->include_style(); ?>
		<? Landing::ShowCode($cms_object->codes->head); ?>
<!-- /HEAD SECTION END -->
