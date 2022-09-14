<?php

namespace App\Http\Requests\Plan;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class PlanRequest extends FormRequest
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
                    'price' => 'required|numeric|min:0|max:2147483647',
                    'price_vn' => 'required|numeric|min:0|max:2147483647',
                    'interval' => 'required|numeric|min:0',
                    'profile' => 'required|numeric|min:0|max:2147483647',
                    'profile_share' => 'required|numeric|min:0|max:2147483647',
                    'describe' => 'max:65535',
                    'status' => 'required|numeric|min:0|max:1'
                ];
            case 'setDefault':
                return [
                    'id' => 'required',
                ];

            default:
                # code...
                break;
        }
        return [
            //
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
            'status.min' => 'The status field malformed.',
            'status.max' => 'The status field malformed.',
        ];
    }
    protected function failedValidation(Validator $validator)
    {

        $errors = $validator->errors()->all();
        throw new HttpResponseException(response()->json(
            [
                'type' => res_type('error'),
                'title' => res_title('validate_error'),
                'content' => $errors,
            ] )
        );
    }

}
