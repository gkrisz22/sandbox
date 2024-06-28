<?php

require __DIR__ . '/app/Request/Request.php';
require __DIR__ . '/app/Request/Response.php';
require __DIR__ . '/app/Router/Route.php';
require __DIR__ . '/app/Router/Router.php';
require __DIR__ . '/app/Middlewares/Middleware.php';
require __DIR__ . '/app/Middlewares/AuthMiddleware.php';
require __DIR__ . '/app/Middlewares/ValidatorMiddleware.php';
require __DIR__ . '/app/Middlewares/SecuredMiddleware.php';

// Setup
use ELME\App\Router\Router;

$router = new Router();

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_POST = json_decode(file_get_contents('php://input'), true);
}