<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidateFile implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public $dot;
    public $att;
    public function __construct($dot = 1)
    {
        $this->dot = $dot;
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
        $this->att = $attribute;
        if (substr_count(($value)??$value->getClientOriginalName(), '.') == $this->dot) {
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
        return $this->att.' wrong format (accept '.$this->dot.' dot).';
    }
}
