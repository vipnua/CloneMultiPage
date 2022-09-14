<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Traits\FileService;

class ExistFile implements Rule
{
    
    private $disk;
    use FileService;
    public function __construct($disk = null)
    {
        $this->disk = $disk;
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
        $file_name = ($value)?$value->getClientOriginalName():'';
        if ($this->fileExists($file_name)) {
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This file is exist.';
    }
}
