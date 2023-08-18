<?php

namespace App\Console\Commands\Making\Execute\MainMake;

use Illuminate\Console\Command;
use App\Console\Commands\Making\RouteConst;
use App\Console\Commands\Making\Execute\Father\DeleteMacro;
use App\Console\Commands\Making\Execute\Father\GenerateMacro;

class MakingWithResourceController extends Command
{
    use GenerateMacro;
    use DeleteMacro;
    protected $stubName='/Admin/Controller';
    protected $rootPath="/Http/Controllers/Admin";
    protected $name = 'Making:MainMake-HandleWithResourceController';
    public function handle()
    {

        if($this->option('delete'))
        {
            $this->executionDelete(RouteConst::APP);
        }
        else
        {
            $prefix=($this->option('page'))?'Page':'List';
            $this->execution(RouteConst::APP,['modelName'=>$this->option('modelName'),'prefix'=>$prefix]); // dd(123);
        }

        // $this->executionWithName(RouteConst::APP,'extra');
        return 0;
    }
}
