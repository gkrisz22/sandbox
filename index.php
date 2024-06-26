<?php

require __DIR__ . '/app/Request/Request.php';
require __DIR__ . '/app/Request/Response.php';
require __DIR__ . '/app/Router/Route.php';
require __DIR__ . '/app/Router/Router.php';
require __DIR__ . '/app/Middlewares/Middleware.php';
require __DIR__ . '/app/Middlewares/AuthMiddleware.php';
require __DIR__ . '/app/Middlewares/ValidatorMiddleware.php';
require __DIR__ . '/app/Middlewares/SecuredMiddleware.php';


use ELME\App\Router\Router;
use ELME\App\Middlewares\AuthMiddleware;
use ELME\App\Middlewares\SecuredMiddleware;
use ELME\App\Request\Request;

$router = new Router();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = json_decode(file_get_contents('php://input'), true);

}

$router->post('/teszt', [new SecuredMiddleware("teszt_kulcs")], function ($dataFromRequest) {
    print_r($dataFromRequest->data);
});


$router->post('/login', [], function ($dataFromRequest) {
    print_r($dataFromRequest->data);
});

function hallgatokListaja(Request $request) {
    print_r($request->data);
}
$router->post('/files/phd_hallgatok_listaja', [new AuthMiddleware()], 'hallgatokListaja');



// Végrehajtás
$router->dispatch($_SERVER['REQUEST_METHOD'], parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
