<?php

namespace App\Http\Requests\Version;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use App\Rules\ValidateFile;
use App\Rules\Version;

class VersionRequest extends FormRequest
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
            'name' => 'required|max:255|unique:versions',
            'version' => ['required', 'max:49', 'unique:versions', new Version()],
            'description' => 'max:65000',
            'remove_file' => 'max:65000',
            'version_file' => ['required', 'file', 'mimes:zip', new ValidateFile()],
        ];
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'Name is required!',
            'name.max' => 'Password is too long!',
            'name.unique' => 'Name already exist!',

            'version.required' => 'Version is required!',
            'version.max' => 'Version is too long!',
            'version.unique' => 'Version already exist!',
            
            'description.max' => 'Description is too long!',

            'remove_file.max' => 'File remove is too long!',

            'version_file.required' => 'Version file is required!',
            'version_file.file' => 'Version file must be a file!',
            'version_file.mimes' => 'Version file wrong format (accept .zip)!',
        ];
    }
    
    /**
     * Override  method failedValidation from FormRequest
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function failedValidation(Validator $validator)
    {

        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('validate_error'),
                'content' => $errors,
            ], res_code(400))
        );
    }
}
