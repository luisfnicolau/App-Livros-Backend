<?php

namespace App\Managers;

use GuzzleHttp\Psr7\Response;

class OpenLibraryException extends Exception
{
    protected $response;
    
    public function __construct(Response $response) {
        $this->response = $response;
        $message = "Status " . $response->getStatusCode() . "\n"
                    . $response->getReasonPhrase() . "\n"
                    . $response->getBody()->getContents();
        parent::__construct($message);
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function __toString() {
        return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
    }
}
