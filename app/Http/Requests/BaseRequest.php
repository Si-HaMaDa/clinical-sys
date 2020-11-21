<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Propaganistas\LaravelPhone\PhoneNumber;

class BaseRequest extends FormRequest
{
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
            return redirect()->back()->with('phone', $e->getMessage());
        }
    }
}
