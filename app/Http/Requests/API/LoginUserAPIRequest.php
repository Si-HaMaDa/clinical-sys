<?php

namespace App\Http\Requests\API;

use App\Models\API\User;
// use InfyOm\Generator\Request\APIRequest;

class LoginUserAPIRequest extends APIRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        // $rules = User::$login_rules;

        $rules = [
            'user' => 'required',
            'password' => 'required|min:6|max:100'
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'password.min' => 'password should be at least 8 numpers or letters',
            'password.required' =>'password required',
        ];
    }
}
