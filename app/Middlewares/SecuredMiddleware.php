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
        $data = $request->data;
        $decrypted_data = $this->decrypt_data($data["data"]);
        if (!$decrypted_data) {
            return new Response(401, ["error" => "Unauthorized: Invalid data."]);
        }
        $request->data = $decrypted_data;
        return $request;
    }

    private function decrypt_data($data)
    {
        list($data, $iv) = explode('::', base64_decode($data), 2);
        return openssl_decrypt($data, 'aes-256-cbc', $this->key, 0, $iv);
    }
}