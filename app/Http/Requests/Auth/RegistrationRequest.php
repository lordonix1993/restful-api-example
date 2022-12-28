<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class RegistrationRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'name'      => 'required|max:255',
            'email'     => 'required|max:255|email:rfc,dns|unique:users,email|',
            'password'  => 'required|min:8|password',
        ];
    }

    /**
     * Handle a failed validation attempt.
     * @param  Validator $validator
     *
     * @return HttpResponseException
     */
    public function failedValidation(Validator $validator): HttpResponseException
    {
        throw new HttpResponseException(response()->json([
            'success'   => false,
            'message'   => __('auth.response.422.validation'),
            'error'     => '',
            'data'      => $validator->errors()
        ], 422));
    }
}