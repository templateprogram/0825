<?php

namespace App\Rules\helper;


trait CheckChinese
{
    private function checkChinese(string &$attribute):void
    {
        $attribute=(preg_match('/\p{Han}/',$attribute))?$attribute:$this->defaultText;
    }
}
