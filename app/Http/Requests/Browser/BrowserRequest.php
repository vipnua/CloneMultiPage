<?php

namespace App\Http\Requests\Browser;

use App\Rules\ValidateFile;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;

class BrowserRequest extends FormRequest
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
                    'config' => 'required',
                ];
                break;

            case 'update':
                return [
                    'uuid' => 'uuid',
                    'config' => 'required',
                    'browser_file' => ['required', 'mimes:zip', new ValidateFile()],
                ];
                break;

            case 'updateBrowser':
                return [
                    'config' => 'required',
                    'browser_file' => ['required', 'mimes:zip', new ValidateFile()],
                ];
                break;

            case 'restore':
                return [
                    'uuid_browser' => 'required',
                    'uuid_browser.*' => 'uuid',
                ];
                break;
        }
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge(['uuid' => $this->route('uuid')]);
    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            /* store */
            'config.required' => 'Config cannot be empty.',

            /* update */
            'uuid.uuid' => 'Uuid wrong format.',
            'browser_file.required' => 'The browser file field is required.',
            'browser_file.mimes' => 'The browser file must be a file of type: zip.',

            /* restore */
            'uuid_browser.required' => 'Browser cannot be empty.',
            'uuid_browser.*.uuid' => 'Browser id must be uuid format',
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
            ], )
        );
    }
}
