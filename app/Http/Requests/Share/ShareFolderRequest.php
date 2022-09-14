<?php

namespace App\Http\Requests\Share;

use App\Rules\Share\UpdateRole;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class ShareFolderRequest extends FormRequest
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
            /* FolderShareController@store */
            case 'store':
                return [
                    'recepient' => ['required', 'email'],
                    'role' => ['required', 'in:guest,admin'],
                    'folder_uuid' => ['required', 'uuid'],
                ];
                break;

            /* FolderShareController@update */
            case 'update':
                return [
                    'role' => ['required', 'in:guest,admin'],
                    'shared_uuid' => ['required', 'uuid', new UpdateRole()],
                ];
                break;

            /* FolderShareController@update */
            case 'destroy':
                return [
                    'shared_uuid' => ['required', 'uuid', new UpdateRole()],
                ];
                break;
        }

    }

    /**
     * Get the validation messages that apply to the request.
     *
     * @return array
     */
    public function messages()
    {
        return [];
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
