<?php

namespace Utils;

class UnknownResponse
{
    private $message;

    public function __construct(\Exception $e)
    {
        $this->message = $e->getMessage();
    }

    public function getStatusCode()
    {
        return 500;
    }

    public function getContent()
    {
        return $this->message;
    }
}
