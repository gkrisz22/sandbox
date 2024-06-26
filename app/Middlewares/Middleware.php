<?php

namespace ELME\App\Middlewares;
use ELME\App\Request\Request;

interface Middleware
{
    public function handle(Request $request);
}