<?php
	global $opt, $pages, $inc, $current, $cms, $root;
?>
<!DOCTYPE html>
<html lang="ru">
	<head>
		<? include($root . '/tmpls/cms/head.inc'); ?>
	</head>
	<body>
		<div class="site_wrap">
			<h1><img src="/favicon.ico" alt=""> <?= $current->title; ?> <div class="version">version: <?= $cms->version; ?></div></h1>
			<div class="content_hided" style="display:none;">
				<?
					if ($cms->logged()):
						$cms->the_cp();
					else:
						$cms->the_login_form();
					endif;
				?>
			</div>
		</div>

		<?= $this->include_js(); ?>
	</body>
</html>