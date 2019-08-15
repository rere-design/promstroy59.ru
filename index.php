<?php

	require $_SERVER['DOCUMENT_ROOT'] . '/inc/options.inc';
	$router = new Router();
	/*$router->add_ab_test(
		'test_1',
		array (
			'apple',
			'test',
		),
		array (
			'apple',
			'test',
		)
	);*/
	$router->init();

?>