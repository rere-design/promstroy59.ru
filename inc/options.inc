<?php

	/*ini_set('error_reporting', E_ALL);
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);*/

	$root = $_SERVER['DOCUMENT_ROOT'];
	$host = $_SERVER['HTTP_HOST'];
	define ('BASE_ROOT', $_SERVER['DOCUMENT_ROOT']);
	define ('CLASS_PATH', $root . '/cls/');

	require_once $root . '/inc/autoload.inc';
	Landing::SetSID();

	$inc = (object) array (
		'send'			=> '/send/form.php',
		'sxgeodb'		=> $root . '/../SxGeoCity.dat',
	);

	$refinfo = new RefInfo();
	$refinfo->init();

	$ab_test = array ();

	require_once $root . '/inc/cms.inc';
	$cms_object = $cms->get_data_object();
	$current = Landing::GetCurrentPage($cms_object->options->pages);
	//vd::dump($cms_object);

?>