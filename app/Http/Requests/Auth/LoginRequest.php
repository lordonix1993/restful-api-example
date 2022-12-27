<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class LoginRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'email'     => 'required|max:255|email:rfc,dns|',
            'password'  => 'required|min:8',
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
            'data'      => $validator->errors()
        ], 422));
    }
}
