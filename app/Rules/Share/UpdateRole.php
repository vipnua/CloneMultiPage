<?php

namespace App\Rules\Share;

use Illuminate\Contracts\Validation\Rule;
use Auth;
use DB;

class UpdateRole implements Rule
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
        $shared = DB::table('userables')->whereUuid($value)->first();
        if ($shared && $shared->sharers_id  == Auth::id()) {
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
        return res_title('notfound_or_permission');
    }
}
