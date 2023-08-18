<?php

namespace App\Rules\RegularRule;

use App\Rules\helper\CheckChinese;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class BackBodyRule implements ValidationRule
{
    use CheckChinese;
    private $defaultText="詳細內容";
    private $maxVal=15000;
    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
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
