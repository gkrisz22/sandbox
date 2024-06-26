<?php

namespace ELME\App\Middlewares;
use ELME\App\Request\Request;

class ValidatorMiddleware implements Middleware
{
    private array $data;
    private Request $request;
    public function __construct($data = [])
    {
        $this->data = $data;
    }
    public function handle(Request $request)
    {
        $request['validator_middlewares'] = 'Validator Middleware';
        $this->request = $request;
        

        $result = $this->validate();
        if (count($result) > 0) {
            $request['errors'] = $result;
        }

        return $request;
    }

    private function validate()
    {
        $errors = [];
        foreach ($this->data as $key => $value) {
            if ($value == "email") {
                $error = $this->validateEmail($this->request->data[$key]);
                if ($error) {
                    $errors[$key] = $error;
                }
            } elseif ($value == "string") {
                $error = $this->validateString($this->request->data[$key]);
                if ($error) {
                    $errors[$key] = $error;
                }
            
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
            return "Invalid string format.";
        }
        return null;
    }


}