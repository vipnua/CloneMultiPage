<?php

namespace App\Http\Requests\Share;

use App\Rules\Share\ExistOwnerBrowser;
use App\Rules\Share\ShareUnregisteredEmail;
use App\Rules\Share\UpdateRole;
use App\Services\Browser\BrowserService;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShareRequest extends FormRequest
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
        $arr = explode('@', $this->route()->getActionName());
        $method = $arr[1];

        /* for BrowserShareController@store*/
        if ($method == 'store') {
            return [
                'recepient' => ['required', 'email', new ShareUnregisteredEmail()],
                'role' => ['required', 'in:guest,admin'],
                'browser_uuid' => ['required', new ExistOwnerBrowser($browserService)],
            ];
        }

        /* for BrowserShareController@update */
        if ($method == 'update') {
            return [
                'role' => ['required', 'in:guest,admin'],
                'share_uuid' => ['required', new UpdateRole()],
            ];
        }

        /* for BrowserShareController@destroy */
        if ($method == 'destroy') {
            return [
                'share_uuid' => ['required', new UpdateRole()],
            ];
        }

    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'recepient.required' => 'Recepient is required!',
            'recepient.email' => 'Recepient email invalid!',

            'role.required' => 'Role is required!',
            'role.in' => 'Role invalid!',

            'browser_uuid.required' => "Uuid is required!",

            'share_uuid' => "Share uuid is required!",
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
