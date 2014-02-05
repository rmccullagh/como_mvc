<?php
define('COMO_START', microtime(true));
switch($_SERVER['COMO_ENV']) {
	case 'DEV':
		define('ENVIROMENT', 'development');
	break;
	default:
		define('ENVIROMENT', 'production');
	break;
}
require  __DIR__ .'/functions.php';
require  __DIR__.'/SplClassLoader.php';




SplClassLoader::autoRegister('Monolog', BASE_PATH . '/vendor/monolog/src');

SplClassLoader::autoRegister('Psr', BASE_PATH .'/vendor/log');

SplClassLoader::autoRegister(NULL, BASE_PATH.'/lib');

#require_once BASE_PATH . '/vendor/monolog/vendor/autoload.php';
