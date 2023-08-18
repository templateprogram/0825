<?php

namespace App\Console\Commands\Making\Handle;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\File;
class MainMake extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $name = 'make:main';
     protected $commandList=[
        'HandleWithApiController'=>['suffix'=>'ApiController'],
        'HandleWithResourceController'=>['suffix'=>'Controller'],
        'HandleWithInertia'=>[],
        'HandleWithRequest'=>['suffix'=>'Request']
    ];
    // protected $commandList=['HandleWithApiController','HandleWithResourceController','HandleWithInertia','HandleWithRequest'];
    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Inertia and api and Controller';

    protected function getArguments() {
        return [
            new InputArgument('foldername', InputArgument::OPTIONAL,'Controller Folder Name')
        ];
    }
    protected function getOptions() {
        /*
        VALUE_NONE=>does not accept option to have a value
        */
        return [
            new InputOption('delete','d',InputOption::VALUE_NONE,'delete a program'),
            new InputOption('page', 'p', InputOption::VALUE_NONE, 'create a page program'),

            // new InputOption('single', 's', InputOption::VALUE_OPTIONAL, 'just Create Controller', true),
        ];
    }
    
    private function modelHandle(string $fileName)
    {
        if($this->option('delete'))
        {
            $migrationFileName=Str::snake(Str::plural($fileName));
            $migratestorage=File::allFiles(database_path('migrations'));
            foreach($migratestorage as $out)
            {
                
                if(preg_match('/(?<=create_)('.Str::plural($migrationFileName).')(?=_table)/',$out))
                {
                    if(File::delete($out))
                    {
                        $this->info('deleted migration:'.$out);
                        break;
                    }
                }
            }
            if(File::delete(app_path("Models/{$fileName}.php")))
            {
                $this->info("deleting model:$fileName");
            }
        }
        else
        {
            $this->call("make:model",['name'=>$fileName,'--migration'=>true]);
            // $this->call('migrate:fresh',['--seed'=>true]);
        }
    }
    /**
     * Execute the console command.
     */
    public function handle()
    {
        // foreach(glob(dirname(__DIR__,1).'/Sub/'))
        $objName=class_basename(__CLASS__);
     
        $filename=$this->ask("what's your filename");
        if(!empty($this->argument('foldername')))
        {
            $name=$this->argument('foldername')."\\{$filename}";
        }
        else
        {
            $name=$filename;
        }
        /*
        */
        
        // dd($this->commandList);
            foreach($this->commandList as $key=>$out)
            {
                $realName=(is_null($out['suffix']??null))?$name:$name.$out['suffix'];

                $this->call('Making:'.$objName.'-'.$key,['name'=>$realName,'--page'=>$this->option('page'),'--delete'=>$this->option('delete'),'--modelName'=>$filename]);
            }
            $this->modelHandle($filename);
 
            // $this->call("make:model $name -m");
    
        return 0;
    }
}
