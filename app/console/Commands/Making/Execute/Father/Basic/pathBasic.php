<?php

namespace App\Console\Commands\Making\Execute\Father\Basic;

use App\Console\Commands\Making\RouteConst;
use Illuminate\Console\GeneratorCommand;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

class pathBasic extends GeneratorCommand{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */

     protected $name = 'Generate:pathBasic';
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
            new InputOption('rootPath','r',InputOption::VALUE_NONE, 'decide generator code namespace start with root Namespace'),
            new InputOption('routeConst','c',InputOption::VALUE_NONE, 'reference for route const decision root path'),
            new InputOption('strReplace','sr',InputOption::VALUE_NONE,'accept json string for replacing')
        ];
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
      * The console command description.
      *
      * @var string
      */
     protected $description = 'reformat generator code for much more easy purpose';
     protected function getStub(){
        return app_path('CustomStub/'.$this->option('stubName').'.stub');
     }
     /**
      * Execute the console command.
      */
      protected function getPath($name)
      {
          /*
          name unused
           */
          $routeConst=$this->option('routeConst');
          if($routeConst!='resource_path')
          {
            return $routeConst($this->option('rootPath').'/'.$this->argument('name').'.php');
          }
        //   $method=RouteConst::{$routeConst}->value;
        return $routeConst($this->option('rootPath').'/'.$this->argument('name').'.vue');
      
        
      }
 
  
}