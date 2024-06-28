<?php

use ELME\App\Middlewares\FormMiddleware;
use ELME\App\Middlewares\SecuredMiddleware;

$router->get('/admin/teszt', [new SecuredMiddleware("teszt_kulcs")], function ($data, $headers) {
    print_r($data);
    // A $data tartalmazza a GET-ben lévő adatokat: /admin/teszt?data=kódolt_sorozat-ban a data dekódolt értéke
});

// Példa: Új bejegyzés létrehozása
$router->post('/admin/bejegyzes', [new FormMiddleware()], function ($data, $headers) {
    echo "Admin teszt: " . $data["name"];
});