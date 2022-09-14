<?php

namespace App\Rules\PaymentMethod;

use Illuminate\Contracts\Validation\Rule;

class checkStringIsNumber implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $attribute;
    public function __construct($param)
    {
        $this->attribute = $param;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
//        dd(is_numeric($value));

        if (is_numeric($value)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The '.str_replace('_',' ',$this->attribute).' must be a number.';
    }
}
