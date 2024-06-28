<?php 

namespace ELME\App\Middlewares;

use ELME\App\Request\Request;
use ELME\App\Request\Response;

class SecuredMiddleware implements Middleware
{
    private $key;

    public function __construct($key)
    {
        $this->key = $key;
    }

    public function handle(Request $request)
    {

    }

    private function decrypt_data($data)
    {

    }
}