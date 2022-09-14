<?php

namespace App\Http\Requests\System;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class PermissionRequest extends FormRequest
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
            'permission_name' => 'required|min:1|max:255|unique:groupermissions,name',
            'description' => 'nullable|max:20000',
            'permission_key' => 'required|min:1|max:255|unique:groupermissions,key',
            'permissions_core' => 'required|array',
            'permissions_core.*' => "required|max:255|regex:/^[a-zA-Z0-9]+$/u",
        ];
        if (isset($this->id)) {
            $rules = [
                'id' => 'required|exists:groupermissions,id',
                'permission_name' => 'required|min:1|max:255|unique:groupermissions,name,' . $this->id,
                'description' => 'nullable||max:20000',
                'permission_key' => 'min:1|max:255|regex:/^[a-zA-Z0-9]+$/u|unique:groupermissions,key,' . $this->id,
            ];
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'id.required' => 'ID is required',
            'id.exists' => 'ID is not found',
            'permission_name.required' => 'Name is required',
            'permission_name.min' => 'Name is too few',
            'permission_name.max' => 'Name is too long',
            'permission_name.unique' => 'Name is existed',
            'description.max' => 'Description is too long',
            'permission_key.min' => 'Key is too few',
            'permission_key.max' => 'Key is too long',
            'permission_key.unique' => 'Key is existed',
            'permission_key.regex' => 'permission is not valid',
            'permissions_core.required' => 'Need a permission',
            'permissions_core.array' => 'Permission is not valid',
            'permissions_core.*.required' => 'permission is not valid',
            'permissions_core.*.max' => 'permission is too long',
            'permissions_core.*.regex' => 'permission is not valid',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'permission_name' => htmlspecialchars(str_replace('"',"'",$this->permission_name)),
            'permission_key' => htmlspecialchars(str_replace('"',"'",$this->permission_key)),
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
