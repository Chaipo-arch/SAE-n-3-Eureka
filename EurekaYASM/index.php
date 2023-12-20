<?php
session_start();
spl_autoload_extensions(".php");
spl_autoload_register();

use yasmf\DefaultComponentFactory;
use yasmf\DataSource;
use yasmf\Router;

$dataSource = new DataSource(
    $host = 'localhost',
    $port = '3306', # to change with the port your mySql server listen to
    $db = 'eureka', # to change with your db name
    $user = 'root', # to change with your db user name
    $pass = 'root', # to change with your db password
    $charset = 'utf8mb4'

);
try {
    $router = new Router(new DefaultComponentFactory) ;
    $router->route($dataSource);
} catch(Exception $e) {
    var_dump($e);
}