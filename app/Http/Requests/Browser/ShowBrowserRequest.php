<?php

namespace App\Http\Requests\Browser;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\ValidationException;
use App\Rules\Share\ExistOwnerBrowser;
use App\Services\Browser\BrowserService;

class ShowBrowserRequest extends FormRequest
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
    public function rules(BrowserService $browserService)
    {
        return [
            'uuid' => ['uuid', new ExistOwnerBrowser($browserService)]
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
            'uuid.uuid' => 'UUID wrong format!',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {  
        $this->merge(['uuid' => $this->route('browser')]);
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
