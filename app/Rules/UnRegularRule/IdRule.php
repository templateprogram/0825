<?php

namespace App\Rules\UnRegularRule;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IdRule implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        if(!(is_int($value))||(int)$value<=0)
        {
            $fail($attribute."必須不為0且為整數");
        }
    }
}
