<?php

namespace App\Http\Requests\System;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class FunctionRequest extends FormRequest
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
            'name' => 'required|min:1|max:255|unique:functions,name',
            'route' => 'nullable|min:1|max:255|unique:functions,route',
            'controller' => 'nullable|min:1|max:255|unique:functions,controller',
            'icon' => 'nullable|max:20000',
            'status' => 'required|in:1,2',
            'description' => 'nullable|max:20000',
            'ordering' => 'required|numeric|max:99999999',
        ];
        if (isset($this->parent_id) && $this->parent_id != 0) {
            $rules['parent_id'] = 'required|numeric|max:99999999999|exists:functions,id';
        }
        if (isset($this->id)) {
            $rules['id'] = 'required|exists:functions,id';
            $rules['name'] = 'required|min:1|max:255|unique:functions,name,' . $this->id;
            $rules['route'] = 'nullable|min:1|max:255|unique:functions,route,' . $this->id;
            $rules['controller'] = 'nullable|min:1|max:255|unique:functions,controller,' . $this->id;
        }
        return $rules;
    }

    public function messages()
    {
        return [
            'id.required' => 'ID is required',
            'id.exists' => 'ID is not found',
            'name.required' => 'Name is required',
            'name.min' => 'Name must be more than 1 characters',
            'name.max' => 'Name is too long',
            'name.unique' => 'Name is existed',
            'route.required' => 'Route is required',
            'route.min' => 'Route must be more than 1 characters',
            'route.max' => 'Route is too long',
            'route.unique' => 'Route is existed',
            'controller.required' => 'Controller is required',
            'controller.min' => 'Controller must be more than 1 characters',
            'controller.max' => 'Controller is too long',
            'controller.unique' => 'Controller is existed',
            'icon.max' => 'Icon is too long',
            'status.in' => 'Status is not valid',
            'description.max' => 'Description is too long',
            'ordering.required' => 'Ordering is required',
            'ordering.numeric' => 'Ordering is not valid',
            'ordering.max' => 'Ordering is too long',
            'parent_id.required' => 'Parent is required',
            'parent_id.exists' => 'Parent is not found',
            'parent_id.numeric' => 'Parent is not valid',
            'parent_id.max' => 'Parent is too long',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => htmlspecialchars(str_replace('"',"'",$this->name)),
            'route' => htmlspecialchars(str_replace('"',"'",$this->route)),
            'controller' => htmlspecialchars(str_replace('"',"'",$this->controller)),
            'icon' => htmlspecialchars(str_replace('"',"'",$this->icon)),
            'status' => htmlspecialchars(str_replace('"',"'",$this->status)),
            'description' => htmlspecialchars(str_replace('"',"'",$this->description)),
            'ordering' => htmlspecialchars(str_replace('"',"'",$this->ordering)),
            'parent_id' => htmlspecialchars(str_replace('"',"'",$this->parent)),
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
