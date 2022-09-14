<?php

namespace App\Rules\Browser;

use Illuminate\Contracts\Validation\Rule;

class CanBeRunning implements Rule
{
    /**
     * Create a new rule instance.
     * Injection browserService
     * @return void
     */
    private $browserService;
    public function __construct($browserService)
    {
        $this->browserService = $browserService;
    }

    /**
     * Determine if the validation rule passes.
     * Check browser can be running
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        /**
         *  If browser is running return response
         *  running : true => Can open the browser
         *  running : false => Can't open because someone already uses it
         */
        $browser = $this->browserService->findBrowserByUuid($value);
        if ( $browser && $browser->can_be_running) {
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
        return "The browser is already being run by another user!";
    }
}
