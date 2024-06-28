<?php
require __DIR__ . '/autoload.php';

// Végpontok betöltése
$routings = glob(__DIR__ . '/routes/*/*.php');
foreach ($routings as $routing) {
    require $routing;
}

// Végrehajtás
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
