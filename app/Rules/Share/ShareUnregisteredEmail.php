<?php

namespace App\Rules\Share;

use Illuminate\Contracts\Validation\Rule;
use App\User;
use Auth;


class ShareUnregisteredEmail implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
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
        $currentAuth = Auth::user();
        $recepient = User::where('email', $value)->first();
        if ($recepient && ($recepient->email != $currentAuth->email)) {
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
        return 'Invalid or unregistered email!';
    }
}
