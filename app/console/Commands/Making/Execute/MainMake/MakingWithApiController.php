<?php

namespace App\Console\Commands\Making\Execute\MainMake;

use Illuminate\Console\Command;
use App\Console\Commands\Making\RouteConst;
use App\Console\Commands\Making\Execute\Father\DeleteMacro;
use App\Console\Commands\Making\Execute\Father\GenerateMacro;

class MakingWithApiController extends Command
{
    use GenerateMacro;
    use DeleteMacro;
    protected $stubName="Api/Controller";
    protected $rootPath="/Http/Controllers/Api";
    protected $name = 'Making:MainMake-HandleWithApiController';
    public function handle()
    {

        if($this->option('delete'))
        {
            $this->executionDelete(RouteConst::APP);
        }
        else
        {

            $this->execution(RouteConst::APP,['modelName'=>$this->option('modelName')]); // dd(123);
        }
        return 0;
    }
}
