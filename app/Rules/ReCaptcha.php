<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Http;

class ReCaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = Http::get("https://www.google.com/recaptcha/api/siteverify", [
            'secret' => env('GOOGLE_SECRET_KEY'),
            'response' => $value,
        ])->json();


        if(!$response['success'])
            $fail("Google ReCaptcha is Invalid");

        // dd($response, $response['success']);
    }
}
