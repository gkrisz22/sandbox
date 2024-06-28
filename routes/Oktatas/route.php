<?php

use ELME\App\Middlewares\AuthMiddleware;
use ELME\App\Middlewares\SecuredMiddleware;

$router->post('/oktatas/teszt', [new SecuredMiddleware("teszt_kulcs")], 'handleOktatasTeszt');

function handleOktatasTeszt($data) {
    print_r($data);
}