<?php

namespace App\Http\Requests\Browser;

use App\Rules\CountDot;
use App\Rules\ExistFile;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Exceptions\HttpResponseException;

class ResourceRequest extends FormRequest
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
        $arr = explode('@', $this->route()->getActionName());
        $action = $arr[1];
        switch ($action) {
            case 'store':
                return [
                    'name' => 'required|max:255',
                    'version' => ['required', 'max:255', 'regex:/^\d+(\.\d+)*$/', 'unique:resources,version', new CountDot(2)],
                    'description' => 'nullable|max:20000',
                    'file' => ['required', 'mimes:zip', new CountDot(), new ExistFile('resource')],
                ];
                break;
            case 'update':
                return [
                    'name' => 'required|max:255',
                    'version' => ['required', 'max:255', 'regex:/^\d+(\.\d+)*$/', 'unique:resources,version,' . $this->id, new CountDot(2)],
                    'description' => 'nullable|max:20000',
                ];
                break;

            case 'changeStatus':
                return [
                    'status' => 'required|in:1,2,3',
                ];
                break;
        }
    }

    public function messages()
    {
        return [
            'name.required' => 'Name is required',
            'name.max' => 'Name is too long',
            'version.required' => 'Version is required',
            'version.max' => 'Version is too long',
            'version.unique' => 'Version is too long',
            'version.regex' => 'Version is invalid',
            'description.max' => 'Description is max',
            'status.required' => 'Status is required',
            'status.in' => 'Status is not valid',
            'file.required' => 'The browser file field is required.',
            'file.mimes' => 'The browser file must be a file of type: zip.',
        ];
    }

    protected function prepareForValidation()
    {
        $this->merge([
            'name' => htmlspecialchars(str_replace('"',"'",$this->name)),
            'version' => htmlspecialchars(str_replace('"',"'",$this->version)),
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
            ],)
        );
    }
}
