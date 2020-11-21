<?php

namespace App\Http\Requests\API;

use App\Exceptions\MyApiException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Arr;
use InfyOm\Generator\Utils\ResponseUtil;
use Response;
use Illuminate\Contracts\Validation\Validator;
use App\Exceptions\MyValidationException;
use Propaganistas\LaravelPhone\PhoneNumber;

class APIRequest extends FormRequest
{

    /**
     * Get the proper failed validation response for the request.
     *
     * @param array $errors
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function response(array $errors)
    {
        $messages = implode(' ', Arr::flatten($errors));

        return Response::json(ResponseUtil::makeError($messages), 400);
    }

    protected function failedValidation(Validator $validator)
    {
        throw new MyValidationException($validator);
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        try {
            if ($this->phone) {
                $this->merge([
                    'phone' => PhoneNumber::make($this->phone, 'EG')->formatE164(),
                ]);
            }
            if ($this->login_field && filter_var($this->login_field, FILTER_VALIDATE_EMAIL) == false) {
                $this->merge([
                    'login_field' => PhoneNumber::make($this->login_field, 'EG')->formatE164(),
                ]);
            }
        } catch (\Exception $e) {
            throw new MyApiException($e->getMessage(), 422);
        }
    }
}
