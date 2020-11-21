<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Contracts\Validation\Validator;

class MyValidationException extends Exception
{
    protected $validator;

    protected $code = 422;

    public function __construct(Validator $validator)
    {
        $this->validator = $validator;
    }

    public function render()
    {
        // $new = array();
        // foreach ($this->validator->errors()->toArray() as $key => $item) {
        //     $new[$key] = implode(',', $item);
        // }

        // return a json with desired format
        return response()->json([
            "success" => false,
            "message" => $this->validator->errors()->first(),
            // "data" => $this->validator->errors()
            // "data" => $new
        ], $this->code);
    }
}
