<?php

namespace App\Rules\Share;

use Auth;
use Illuminate\Contracts\Validation\Rule;

class ExistOwnerBrowser implements Rule
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
     * Check if exist browser and have owner with browser
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $browser = $this->browserService->findBrowserByUuid($value);
        if ($browser && $this->browserService->checkUserOwnedBrowser($browser, Auth::user())) {
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
