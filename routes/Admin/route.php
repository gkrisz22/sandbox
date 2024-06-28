<?php

use ELME\App\Middlewares\SecuredMiddleware;

$router->get('/admin/teszt', [], function ($data, $headers) {
    //echo "Admin teszt: " . $data["name"];
    print_r($data);
});

$router->post('/Admin/teszt', [new SecuredMiddleware("teszt_kulcs")], function ($data, $headers) {
    echo "Admin teszt: " . $data["name"];
});