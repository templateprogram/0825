<?php

namespace App\Rules\RegularRule;

use Closure;
use App\Rules\helper\CheckChinese;
use Illuminate\Contracts\Validation\ValidationRule;

class NameRule implements ValidationRule
{
    use CheckChinese;
    private $defaultText="標題";
    private $maxVal=191;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        //
        $this->checkChinese($attribute);

        if(!is_string($value))
        {
            $fail($attribute."必須為字串");
        }
        if(strlen($value)>$this->maxVal)
        {
            $fail($attribute."字串長度最大:".$this->maxVal);
        }
        

    
    }
    
}
