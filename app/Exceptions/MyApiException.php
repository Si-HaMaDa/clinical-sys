<?php

namespace App\Exceptions;

use Exception;

class MyApiException extends Exception
{
    protected $message;

    protected $code;

    public function __construct($message, $code = '500')
    {
        $this->message = $message;
        $this->code = $code;
    }

    public function render()
    {
        // return a json with desired format
        return response()->json([
            "success" => false,
            "message" => $this->message
        ], $this->code);
    }
}
