<?php

namespace App\Http\Requests\Feedback;

// use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
// use Illuminate\Http\Exceptions\HttpResponseException;

class FeedbackRequest extends FormRequest
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

}
