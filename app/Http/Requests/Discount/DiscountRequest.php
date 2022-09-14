<?php

namespace App\Http\Requests\Discount;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class DiscountRequest extends FormRequest
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
            case 'update':
                return [
                    'name' => 'required|max:255',
                    'code' => 'required|max:255',
                    'max_use' => 'required|numeric|min:0|max:2147483647',
                    'profile' => 'nullable|numeric|min:0|max:2147483647',
                    'share' => 'nullable|numeric|min:0|max:2147483647',
                    'date' => 'nullable|numeric|min:0|max:2147483647',
                    'price' => 'nullable|numeric|min:0|max:100',
                    'date_from' => 'required|date',
                    'date_to' => 'required|date'
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
            'date_from.required' => 'The date from is required.',
            'date_from.date' => 'The date from is invalid.',
            'date_to.required' => 'The expired is required.',
            'date_to.date' => 'The expired from is invalid.',
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
