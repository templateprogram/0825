<?php

namespace App\Console\Commands\Making\Execute\MainMake;

use Illuminate\Console\Command;
use App\Console\Commands\Making\RouteConst;
use Symfony\Component\Console\Input\InputOption;
use App\Console\Commands\Making\Execute\Father\DeleteMacro;
use App\Console\Commands\Making\Execute\Father\GenerateMacro;

class MakingWithRequest extends Command
{
    use GenerateMacro;
    use DeleteMacro;
    protected $stubName='Admin/Request';
    protected $rootPath="/Http/Requests/Admin";

    protected $name = 'Making:MainMake-HandleWithRequest';
    public function handle()
    {
        if($this->option('delete'))
        {
            $this->executionDelete(RouteConst::APP);
        }
        else
        {
            $this->execution(RouteConst::APP); // dd(123);
        }

        return 0;
    }
}
