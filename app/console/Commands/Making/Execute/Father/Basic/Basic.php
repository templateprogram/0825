<?php
namespace App\Console\Commands\Making\Execute\Father\Basic;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class Basic extends GeneratorCommand{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     protected $name = 'Generate:Basic';
    //  protected $signature = 'Generate:Basic {name?}';
    protected function getArguments() {
        return [
            new InputArgument('name', InputArgument::OPTIONAL,	'Generator Code fileName')
            // customstub/???
        ];
    }
    protected function getOptions() {
        /*
        VALUE_NONE=>does not accept option to have a value
        */
        return [
            new InputOption('stubName','s',InputOption::VALUE_NONE, 'stub file store location  start with custom stub'),
            new InputOption('rootPath','r',InputOption::VALUE_NONE, 'decide generator code namespace'),
            new InputOption('strReplace','sr',InputOption::VALUE_NONE,'accept json string for replacing')
        ];
    }
     /**
      * The console command description.
      *
      * @var string
      */
     protected $description = 'reformat generator code for much more easy purpose';
     protected function getStub(){
        return app_path('CustomStub/'.$this->option('stubName').'.stub');
     }
     protected function buildClass($name): string
    {
        $stub = $this->files->get($this->getStub());
       
      
        return $this->replaceNamespace($stub, $name)->replaceClass(
            $this->strReplacing($stub),
            $name
        );
    }
    protected function strReplacing($stub)
    {
        
        $strRelation=json_decode($this->option('strReplace'),true);
        foreach($strRelation as $key=>$out)
        {
            $stub=str_replace(["{{ $key }}","{{$key}}"],$out,$stub);
        }
        return $stub;
    }
     /**
      * Execute the console command.
      */
     public function getDefaultNamespace($rootNameSpace)
     {
        // dd($rootNameSpace.$this->option('rootPath'));
        return $rootNameSpace.$this->option('rootPath');
     }
 
  
}