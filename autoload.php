<?php


require __DIR__ . '/app/Request/Request.php';
require __DIR__ . '/app/Request/Response.php';
require __DIR__ . '/app/Router/Route.php';
require __DIR__ . '/app/Router/Router.php';
require __DIR__ . '/app/Middlewares/Middleware.php';
require __DIR__ . '/app/Middlewares/AuthMiddleware.php';
require __DIR__ . '/app/Middlewares/ValidatorMiddleware.php';
require __DIR__ . '/app/Middlewares/SecuredMiddleware.php';
require __DIR__ . '/app/Middlewares/FormMiddleware.php';

// Setup
use ELME\App\Router\Router;

$router = new Router();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(empty($_POST)) {
        $_POST = json_decode(file_get_contents('php://input'), true);
    }
}

// CORS és session kezelés
setcookie('SameSite', 'None', 0, '/', '', true, true);

$origins = [
    'https://elmetest.inf.elte.hu',
    'http://elmetest.inf.elte.hu',
    'http://localhost:8080',
];


if(isset($_SERVER['HTTP_ORIGIN']) && in_array($_SERVER['HTTP_ORIGIN'], $origins) || DEBUG) {
    header('Access-Control-Allow-Origin: ' . $_SERVER['HTTP_ORIGIN']);
    header('Access-Control-Allow-Credentials: true');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
}
else {
    header('HTTP/1.1 403 Forbidden');
    exit;
}