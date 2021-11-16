<?php

namespace App\Http\Requests\User;

use App\Models\User;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreUserRequest extends FormRequest
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
        return [
            'email'     => 'unique:users,email|email|required',
            'name'      => 'required|string|min:3',
            'password'  => 'required|string|min:6|confirmed'
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */

    public function withValidator($validator)
    {
        if ($validator->fails()) {
            throw new HttpResponseException(response()->json([
                'message'   => 'Ops! Algo inesperado aconteceu, tente novamente.',
                'status' => false,
                'errors'    => $validator->errors(),
                'url'    => route('users.store')
            ], 403));
        }
    }
}
