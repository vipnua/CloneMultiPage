<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserRequest extends FormRequest
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
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|min:6|max:255|confirmed',
        ];
        if (isset($this->id)) {
            $rules['id'] = 'required|exists:users,id';
            $rules['name'] = 'required|max:255';
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->id;
            $rules['password'] = 'nullable|min:6|max:255|confirmed';
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'id.required' => 'ID is required',
            'id.exists' => 'ID is not found',
            'name.required' => 'Name is required',
            'name.max' => 'Name is too long',
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.max' => 'Email is too long',
            'email.unique' => 'Email is existed',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be more than 6 characters',
            'password.max' => 'Password is too long',
            'password.confirmed' => 'Confirm password is not correct',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => htmlspecialchars(str_replace('"',"'",$this->name)),
            'email' => htmlspecialchars(str_replace('"',"'",$this->email)),
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
            ],)
        );
    }

}
