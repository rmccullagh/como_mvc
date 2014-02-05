<?php
define('COMO_START', microtime(true));
$ENV = isset($_SERVER['COMO_ENV']) ? $_SERVER['COMO_ENV'] : 'DEV';
switch($ENV) {
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

SplClassLoader::autoRegister('Lib', BASE_PATH.'/library/');

SplClassLoader::autoRegister('Model', BASE_PATH .'/model/');

SplClassLoader::autoRegister(NULL, BASE_PATH.'/lib');

#require_once BASE_PATH . '/vendor/monolog/vendor/autoload.php';
