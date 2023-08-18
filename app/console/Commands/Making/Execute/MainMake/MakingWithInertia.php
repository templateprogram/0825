<?php

namespace App\Console\Commands\Making\Execute\MainMake;

use Illuminate\Console\Command;
use App\Console\Commands\Making\RouteConst;
use App\Console\Commands\Making\Execute\Father\DeleteMacro;
use App\Console\Commands\Making\Execute\Father\GenerateMacro;

class MakingWithInertia extends Command
{
    use GenerateMacro;
    use DeleteMacro;
    protected $stubName=null;
    protected $rootPath="/js/Pages/Admin";

    protected $name = 'Making:MainMake-HandleWithInertia';
    public function handle()
    {
        if($this->option('page'))
        {
            $this->rootPath.="/Page";
        }
        else
        {
            $this->rootPath.="/List";
        }
        if($this->option('delete'))
        {
            $this->executionDelete(RouteConst::RESOURCE);
        }
        else
        {
            $this->stubName=($this->option('page'))?'Inertia/Page':'Inertia/List';
        
            $lowerName=strtolower($this->option('modelName'));
            $this->execution(RouteConst::RESOURCE,['namePrefixOfRoute'=>"admin-{$lowerName}"]);
        }

            // $lowerName=strtolower($this->argument('name'));
            // $this->execution(RouteConst::RESOURCE,['remoteRouteNamePrefix'=>"admin-{$lowerName}"]);
        
        return 0;
    }
}
