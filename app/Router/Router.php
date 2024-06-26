<?php

namespace ELME\App\Router;

use ELME\App\Request\Request;

class Router
{
    /**
     * Az összes végpontot tároló tömb
     * @var Route[] $routes  A végpontokat tartalmazó tömb
     */
    private $routes = [];

    /**
     * Új GET végpont hozzáadása
     * @param string $path                  A végpont neve
     * @param Middleware[] $middlewares     A végponton alkalmazandó middleware-ek
     * @param callable $handler             Feldolgozó függvény
     */
    public function get($path, $middlewares, $handler)
    {
        $this->routes[] = new Route('GET', $path, $middlewares, $handler);
    }

    /**
     * Új POST végpont hozzáadása
     * @param string $path                  A végpont neve
     * @param Middleware[] $middlewares     A végponton alkalmazandó middleware-ek
     * @param callable $handler             Feldolgozó függvény
     */
    public function post($path, $middlewares, $handler)
    {
        $this->routes[] = new Route('POST', $path, $middlewares, $handler);
    }

    /**
     * A megfelelő végpont kiválasztása és végrehajtása
     * @param string $requestMethod     A kérés metódusa
     * @param string $requestPath       A kérés útvonala
     */
    public function dispatch($requestMethod, $requestPath)
    {
        $data = $_POST ?? [];
        $data = array_merge($data, $_GET ?? [], $_FILES ?? []);
        
        $request = new Request($data, getallheaders());

        foreach ($this->routes as $route) {
            if ($route->match($requestMethod, $requestPath)) {
                $route->execute($request);
                return;
            }
        }
        http_response_code(404);
        echo json_encode(['error' => 'Route not found']);
    }
}
