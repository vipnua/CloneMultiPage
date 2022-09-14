<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class AdminRequest extends FormRequest
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
            'email' => 'required|email|max:255|unique:admins',
            'password' => 'required|min:6|max:255|confirmed',
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'gender' => 'required|in:1,2,3',
            'birthday' => 'required|date',
            'address' => 'required|max:255',
            'roles' => 'required|array',
          /*   'detail' => 'required', */
        ];
        if (isset($this->id)) {
            $rules['id'] = 'required|exists:users,id';
            $rules['email'] = 'required|email|max:255|unique:users,email,' . $this->id;
            $rules['password'] = 'nullable|min:6|max:255|confirmed';
            if (isset($this->reset_value)) {
                unset($rules['email']);
            }
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'id.required' => 'ID is required',
            'id.exists' => 'ID is not found',
            'email.required' => 'Email is required',
            'email.email' => 'Email is not valid',
            'email.max' => 'Email is too long',
            'email.unique' => 'Email is existed',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be more than 6 characters',
            'password.max' => 'Password is too long',
            'password.confirmed' => 'Confirm password is not correct',
            'first_name.required' => 'First name is required',
            'first_name.max' => 'First name is too long',
            'last_name.required' => 'Last name is required',
            'last_name.max' => 'Last name is too long',
            'gender.required' => 'Gender is required',
            'gender.in' => 'Gender is not valid',
            'birthday.required' => 'Birthday is required',
            'birthday.date' => 'Birthday is not valid',
            'address.required' => 'Address is required',
            'address.max' => 'Address is too long',
       /*      'detail.required' => 'Detail is not valid', */
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'email' => htmlspecialchars(str_replace('"',"'",$this->email)),
            'first_name' => htmlspecialchars(str_replace('"',"'",$this->first_name)),
            'last_name' => htmlspecialchars(str_replace('"',"'",$this->last_name)),
            'address' => htmlspecialchars(str_replace('"',"'",$this->address)),
            'gender' => htmlspecialchars(str_replace('"',"'",$this->gender)),
            'birthday' => htmlspecialchars(str_replace('"',"'",$this->birthday)),
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
