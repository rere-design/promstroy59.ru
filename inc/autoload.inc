<?php

	spl_autoload_register(function ($class_name) {
		$file = CLASS_PATH . strtolower($class_name);
		if (file_exists($file . '.class.php')) {
			require_once $file . '.class.php';
		} elseif (file_exists($file . '.inc.php')) {
			require_once $file . '.inc.php';
		} elseif (file_exists($file . '.php')) {
			require_once $file . '.php';
		} //else die('Отсутствует класс "' . strtolower($class_name) . '" в директории "' . CLASS_PATH . '"');
	});

?>