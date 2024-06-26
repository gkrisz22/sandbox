<?php 


namespace ELME\App\Request;

class Response
{
    public $body;
    public $status;

    public function __construct($status, $body)
    {
        $this->body = $body;
        $this->status = $status;
        $this->send();
    }

    public function send()
    {
        http_response_code($this->status);
        echo json_encode($this->body);
    }
}