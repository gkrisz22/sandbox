<?php

namespace ELME\App\Middlewares;

class RequestMiddleware implements Middleware
{
    public function handle($request)
    {
        $request['request_middlewares'] = 'Request Middleware';

        return $request;
    }
}