<?php

namespace App\Http\Requests\Manager;

use App\Model\PaymentTransaction;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserPlanRequest extends FormRequest
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
            'user' => 'required',
            'plan' => 'required',
            'payment_method' => 'required',
            'amount' => 'required|',
            'currency' => ['required',Rule::in(PaymentTransaction::CURRENCY)],
            'transaction' => 'required',
            'note' => 'max:100000',

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
            ],)
        );
    }

}
