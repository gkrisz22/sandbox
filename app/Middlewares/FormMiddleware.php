<?php 

namespace ELME\App\Middlewares;

use ELME\App\Request\Request;
use ELME\App\Request\Response;

class FormMiddleware implements Middleware
{

    public function handle(Request $request)
    {
        if(session_status() == PHP_SESSION_NONE)
            session_start();
        
        if(empty($_POST['token']) || $_POST['token'] !== $_SESSION['csrf_token']) {
            return new Response(403, ['error' => 'Invalid CSRF token']);
        }

        return $request;
    }

}