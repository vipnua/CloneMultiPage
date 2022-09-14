<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class RoleRequest extends FormRequest
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
        if (isset($this->select)) {
            return $rules = [
                    'id' => 'required|exists:roles,id',
                    'select' => 'required',
                    'user_id' => 'required|exists:users,id',
                ];
        }
        if (is_numeric($this->id)) {
            if (isset($this->id)) {
                $rules = [
                    'id' => 'required|exists:roles,id',
                    'role_name' => 'required|min:1|max:255|unique:roles,name,' . $this->id,
                    'description' => 'nullable||max:20000',
                    'permission' => 'nullable|array',
                    'permission.*' => "required|max:255|regex:/^[0-9]+$/u",
                ];
            }
            return $rules;
        }
        return $rules = [
            'role_name' => 'required|min:1|max:255|unique:roles,name',
            'description' => 'nullable|max:20000',
            'permission' => 'nullable|array',
            'permission.*' => "required|max:255|regex:/^[0-9]+$/u",
        ];
    }

    public function messages()
    {
        return [
            'id.required' => 'ID is required',
            'id.exists' => 'ID is not found',
            'role_name.required' => 'Name is required',
            'role_name.min' => 'Name is too few',
            'role_name.max' => 'Name is too long',
            'role_name.unique' => 'Name is existed',
            'description.max' => 'Description is too long',
            'permission.required' => 'Need a permission',
            'permission.array' => 'Permission is not valid',
            'permission.*.required' => 'permission is not valid',
            'permission.*.max' => 'permission is too long',
            'permission.*.regex' => 'permission is not valid',
            'select.required' => 'User not found',
            'user_id.required' => 'User is required'
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'permission_name' => htmlspecialchars(str_replace('"',"'",$this->permission_name)),
            'description' => htmlspecialchars(str_replace('"',"'",$this->description)),
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
            ])
        );
    }
}
