<?php

namespace App\Http\Requests\Manager;

use App\Model\PaymentMethod;
use App\Rules\PaymentMethod\checkStringIsNumber;
use App\Rules\ValidateFile;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\RequiredIf;

class PaymentMethodRequest extends FormRequest
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
                    'method' => ['required', Rule::in(array_keys(PaymentMethod::METHOD))],
                    'country' => ['required', Rule::in(PaymentMethod::COUNTRY)],
                    'status' => ['required', Rule::in(array_keys(PaymentMethod::STATUS))],
                    'name' => ['required', 'max:255'],

                    'account_number' => [new RequiredIf($this->method == 'bank'), new checkStringIsNumber('bank number')],
                    'branch_name' => [new RequiredIf($this->method == 'bank'), 'max:255'],
                    'icon' => [new RequiredIf($this->type == 'bank'), 'mimes:jpeg,png,jpg'],

                    'client_id' => [new RequiredIf($this->method == 'paypal'), 'max:255'],
                    'secret_key' => [new RequiredIf($this->method == 'paypal'), 'max:255'],
                    'paypal_sandbox' => [new RequiredIf($this->method == 'paypal')],
                ];
                break;

            case 'update':
                return [
                    'method' => ['required', Rule::in(array_keys(PaymentMethod::METHOD))],
                    'country' => ['required', Rule::in(PaymentMethod::COUNTRY)],
                    'status' => ['required', Rule::in(array_keys(PaymentMethod::STATUS))],
                    'name' => ['required', 'max:255'],

                    'account_number' => [new RequiredIf($this->method == 'bank'), new checkStringIsNumber('bank number')],
                    'branch_name' => [new RequiredIf($this->method == 'bank'), 'max:255'],
                    'icon' => ['mimes:jpeg,png,jpg'],

                    'client_id' => [new RequiredIf($this->method == 'paypal'), 'max:255'],
                    'secret_key' => [new RequiredIf($this->method == 'paypal'), 'max:255'],
                    'paypal_sandbox' => [new RequiredIf($this->method == 'paypal')],
                ];
                break;

        }
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
