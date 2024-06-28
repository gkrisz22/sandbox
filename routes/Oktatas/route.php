<?php

use ELME\App\Middlewares\AuthMiddleware;
use ELME\App\Middlewares\FormMiddleware;
use ELME\App\Middlewares\SecuredMiddleware;
use ELME\App\Middlewares\ValidatorMiddleware;

$router->post('/oktatas/teszt', [new FormMiddleware(), new ValidatorMiddleware(array("szoveg" => "string", "szam" => "number"))], 'handleOktatasTeszt');

function handleOktatasTeszt($data) {
    echo "POST /oktatas/teszt";
    print_r($data);

}