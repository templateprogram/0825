<?php

namespace App\Console\Commands\Making\Execute\Father;
use App\Console\Commands\Making\RouteConst;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;

trait GenerateMacro{
    private $calledFlag=false;
    protected function getArguments() {
        return [
            new InputArgument('name', InputArgument::REQUIRED,	'Generator Code fileName'),

        ];
    }
    protected function getOptions() {
        /*
        VALUE_NONE=>does not accept option to have a value
        */
        return [
            new InputOption('delete','d',InputOption::VALUE_NONE,'delete a program'),
            new InputOption('page', 'p', InputOption::VALUE_NONE, 'create a page program'),
            new InputOption('modelName', null, InputOption::VALUE_REQUIRED, 'record of the real file name '),
            // new InputOption('single', 's', InputOption::VALUE_OPTIONAL, 'just Create Controller', true),
        ];
    }
    // protected function avoidShallowCopyGenerate(string $functionName):void
    // {
    //     if(!$this->calledFlag)
    //     {
    //         switch($functionName)
    //         {
    //             case "executionWithName":

    //                 $this->rootPath.='/'.$this->argument('name');
    //             break;
    //             default:

    //             break;
    //             // default:
    //             //     $this->rootPath.='/'.config('constants.ApiVersion')??'v1';
    //             // break;
    //         }
    //     }
    //     $this->calledFlag=true;
        
   
    // }
    protected function execution(RouteConst $basePath,array $array=[])
    {
        // $this->avoidShallowCopyGenerate(__FUNCTION__);

        $references=[
            'name'=>$this->argument('name'),
            '--stubName'=>$this->stubName,
            '--rootPath'=>$this->rootPath,
            '--strReplace'=>json_encode($array),
        ];
        // return ($basePath->name=='APP')?$this->call('Generate:Basic',$references):$this->call
        $this->calling($references,$basePath);
    }
    protected function calling($references,RouteConst $basePath)
    {

        if($basePath->name=='APP')
        {
            return $this->call('Generate:Basic',$references);
        }
        else
        {
            // TODO some problem
            // dd($references);
            $references=array_merge($references,['--routeConst'=>$basePath->value]);
            return $this->call('Generate:pathBasic',$references);
        }
    }
    protected function executionWithName(RouteConst $basePath,$name,array $array=[])
    {
        // $this->avoidShallowCopyGenerate(__FUNCTION__);
        $references=[
            'name'=>$name,
            '--stubName'=>$this->stubName,
            '--rootPath'=>$this->rootPath,
            '--strReplace'=>json_encode($array),
        ];
        $this->calling($references,$basePath);
        // return ($view)?$this->call('Generate:ViewBasic',$references):$this->call('Generate:Basic',$references);
    }
}