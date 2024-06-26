<?php

namespace ELME\App\Router;

use ELME\App\Request\Request;

/**
 * Egy végpontot reprezentáló osztály
 * @package Elme\App
 */
class Route
{
    /**
     * @var string  $method The HTTP method
     * @var string  $path The URL path
     * @var array   $middlewares The middlewares to be executed
     * @var void    $handler The handler to be executed
     */
    private $method;
    private $path;
    private $middlewares;
    private $handler;

    /**
     * Végpont konstruktor
     * @param string $method        A HTTP metódus
     * @param string $path          Az URL útvonal
     * @param array $middlewares    A végponton alkalmazandó middleware-ek
     * @param callable $handler     Feldolgozó függvény
     */
    public function __construct($method, $path, $middlewares, $handler)
    {
        $this->method = $method;
        $this->path = $path;
        $this->middlewares = $middlewares;
        $this->handler = $handler;
    }

    /**
     * A végponttal való egyezés vizsgálata
     * @param string $requestMethod     A kérés metódusa
     * @param string $requestPath       A kérés útvonala
     * @return bool                     Az egyezés eredménye
     */
    public function match($requestMethod, $requestPath)
    {
        return $this->method === $requestMethod && $this->path === $requestPath;
    }

    /**
     * A végpont végrehajtása
     * @param array $request    A kérés adatai
     */
    public function execute(Request $request)
    {
        foreach ($this->middlewares as $middleware) {
            $res = $middleware->handle($request);
            if ($res !== null) {
                $request = $res;
            }
        }

        ($this->handler)($request);
    }
}
