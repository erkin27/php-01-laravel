<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class Phone implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! preg_match('/\(?(\d{3})\)?([ .-]?)(\d{3})([ .-]?)(\d{2})([ .-]?)(\d{2})/', $value)) {
            $fail("$attribute must have at least 10 digits");
        }
    }
}
