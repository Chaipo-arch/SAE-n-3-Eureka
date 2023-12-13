<?php
const PREFIX_TO_RELATIVE_PATH = "\SaeWeb\EurekaYASM";
echo  $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';
require $_SERVER[ 'DOCUMENT_ROOT' ] . PREFIX_TO_RELATIVE_PATH . '/lib/vendor/autoload.php';

use application\DefaultComponentFactory;
use yasmf\DataSource;
use yasmf\Router;

$dataSource = new DataSource(
    $host = 'mezabi-1-db',
    $port = '3306', 
    $db = 'mezabi-1', 
    $user = 'mezabi-1', 
    $pass = 'mezabi-1', 
    $charset = 'utf8mb4'
);

$router = new Router(new DefaultComponentFactory(), $dataSource) ;
$router->route(PREFIX_TO_RELATIVE_PATH, $dataSource);
