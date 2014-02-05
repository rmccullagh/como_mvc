<?php
define('BASE_PATH', realpath(dirname(__FILE__)));

define('VIEW_PATH', BASE_PATH . DIRECTORY_SEPARATOR . 'view');

define('EXT', '.php');
define('LOG_FILE', BASE_PATH.'/logs/');

require __DIR__.'/bootstrap/autoload.php';

use Monolog\Logger;
use Monolog\Handler\StreamHandler;

$log = new Logger('app');

$log->pushHandler(new StreamHandler(BASE_PATH.'/logs/app.log',  Logger::DEBUG));
$log->addInfo("Initialzing application @ " . COMO_START);

$request = new \Request();
$router  = new \Router();

$dispatcher = new \Dispatcher($request, $router);
$dispatcher->prepare();
$dispatcher->execute();

$memory = round(memory_get_usage() / 1024 / 1024,  2).'MB';

$log->addInfo("Memory usage: " . $memory);
$tet = microtime(true) - COMO_START;

$log->addInfo("Execution Time: " . $tet);





