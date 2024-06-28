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
        if(empty($request->data)) {
            return new Response(400, ["error" => "Bad request: no data found in request"]);
        }
        $data = $request->data;

        $decrypted_data = $this->decrypt_data($data["data"]);

        if (!$decrypted_data) {
            $request->data = [];
            return new Response(401, ["error" => "Invalid credentials"]);
        }

        if($decrypted_data === -1) {
            return new Response( 400, ["error" => "Bad request: invalid data"]);
        }

        $request->data = json_decode($decrypted_data, true);
        return $request;
    }

    private function decrypt_data($data)
    {
        $decoded_data = base64_decode($data, true);
        if ( $decoded_data === false) {
            return -1;
        }
        $parts = explode('::', $decoded_data, 2);
        if (count($parts) !== 2) {
            return -1;
        }

        list($encrypted_data, $iv) = $parts;

        if(strlen($iv) !== 16) {
            return -1;
        }

        $decrypted = openssl_decrypt($encrypted_data, 'aes-256-cbc', $this->key, 0, $iv);
        return $decrypted;
    }
}