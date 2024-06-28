<?php

namespace ELME\App\Middlewares;
use ELME\App\Request\Request;
use ELME\App\Request\Response;

class ValidatorMiddleware implements Middleware
{
    private array $data;
    private Request $request;

    // Példa $data = ['email' => 'email', 'password' => 'string']
    public function __construct($data = [])
    {
        $this->data = $data;
    }
    public function handle(Request $request)
    {
        $this->request = $request;
        $this->sanitize_data();

        $result = $this->validate();
        if (count($result) > 0) {
            return new Response(400, $result);
        }

        return $request;
    }

    public function sanitize_data() {
        foreach ($this->data as $key => $value) {
            switch ($value) {
                case "email":
                    $this->request->data[$key] = filter_var($this->request->data[$key], FILTER_SANITIZE_EMAIL);
                    break;
                case "string":
                    $this->request->data[$key] = filter_var($this->request->data[$key], FILTER_SANITIZE_STRING);
                    break;
                case "number":
                    $this->request->data[$key] = filter_var($this->request->data[$key], FILTER_SANITIZE_NUMBER_INT);
                    break;
            }
        }
    }

    private function validate()
    {
        $errors = [];
        foreach ($this->data as $key => $value) {
            switch ($value) {
                case "email":
                    $error = $this->validateEmail($this->request->data[$key]);
                    
                    break;
                case "string":
                    $error = $this->validateString($this->request->data[$key]);
                    break;
                case "number":
                    $error = $this->validateNumber($this->request->data[$key]);
                    break;
            }

            if ($error) {
                $errors[$key] = $error;
            }

        }
        return $errors;
    }

    private function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return "Invalid email format.";
        }
        return null;
    }

    private function validateString($string)
    {
        if (!is_string($string)) {
            return "Not a string.";
        }
        return null;
    }

    private function validateNumber($number)
    {
        if(filter_var($number, FILTER_VALIDATE_INT) === false) {
            return "Not an integer.";
        }
        return null;
    }


}