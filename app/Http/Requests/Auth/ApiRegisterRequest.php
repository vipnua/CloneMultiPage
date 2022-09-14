<?php

namespace App\Http\Requests\Auth;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ApiRegisterRequest extends FormRequest
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
        $rules = [
            'email' => 'required|email|max:150|unique:users',
            'password' => 'required|min:6|max:255|confirmed',
            'name' => 'required|max:150',
        ];
        return $rules;
    }

    public function messages()
    {
        return [
            'email.required' => 'Email is required!',
            'email.email' => 'Email is not valid!',
            'email.max' => 'Email is too long!',
            'email.unique' => 'Email is existed!',

            'password.required' => 'Password is required!',
            'password.min' => 'Password must be more than 6 characters!',
            'password.max' => 'Password is too long!',
            'password.confirmed' => 'Password confirm is incorrect!',

            'name.required' => 'Name is required!',
            'email.max' => 'Name is too long!!',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'email' => htmlspecialchars(str_replace('"', "'", $this->email)),
        ]);
    }

    protected function failedValidation(Validator $validator)
    {

        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('validate_error'),
                'content' => $errors,
            ], )
        );
    }

}
