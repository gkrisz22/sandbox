<?php

namespace ELME\App\Request;

class Request
{
    public $data = [];
    public $headers;

    public function __construct($data, $headers)
    {
        $this->data = $data;
        $this->clean_data();
        $this->headers = $headers;
    }

    private function clean_data()
    {
        $cleaned_data = [];
        foreach ($this->data as $key => $value) {
            $cleaned_data[$key] = htmlspecialchars($value);
        }
        return $cleaned_data;
    }

    public function getHeader($name)
    {
        return $this->headers[$name] ?? null;
    }
}
