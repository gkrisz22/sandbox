<?php 


namespace ELME\App\Middlewares;

use ELME\App\Request\Request;
use ELME\App\Request\Response;

class AuthMiddleware implements Middleware
{
    public function handle(Request $request)
    {
        // Bearer token féle validáció, de lecserélhető bármire, akár session-re is
        $authHeader = $request->getHeader('Authorization');

        if (!$authHeader) {
            return new Response(401, ["error" => "Unauthorized: Missing Authorization header."]);
        }

        list($type, $token) = explode(' ', $authHeader, 2);

        if ($type !== 'Bearer' || !$this->validateToken($token)) {
            return new Response(401, ["error" => "Unauthorized: Invalid token."]);
        }
        return $request;
    }

    private function validateToken($token)
    {
        return $token === 'valid-token';
    }
}