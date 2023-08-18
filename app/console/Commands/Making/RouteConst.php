<?php

namespace App\Console\Commands\Making;


enum RouteConst:string{
    case BASE="base_path";
    case RESOURCE="resource_path";
    case APP="app_path";
    case STORAGE="storage_path";
    public static function getCase(string $selected):self|null
    {
        foreach(self::cases() as $out)
        {
            if($selected==$out->name)
            {
                return $out;
            }
        }
        return null;
    }
}