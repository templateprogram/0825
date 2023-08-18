<?php

namespace App\Console\Commands\Making\Execute\Father;
use Illuminate\Support\Facades\File;
use App\Console\Commands\Making\RouteConst;

trait DeleteMacro{
    protected $currentdirectory=null;
    private $calledFlag=false;
    protected function trimoutDoubleSlashes():void
    {
        $this->currentdirectory=preg_replace('/\/\//','/',$this->currentdirectory);
    }
    protected function unlinkdirectories($directory=null):callable|bool
    {
        // 如果沒有新的資料夾進行傳參，將不會指派新的資料夾給目前的資料夾。
        if(!is_null($directory))
        {
            $this->currentdirectory=$directory;
        }
        $this->trimoutDoubleSlashes();
        // 若此層資料夾為空 繼續向上找 看當時的資料夾是否為空
    
        if(count(glob($this->currentdirectory."*"))==0)
        {
            
            $nextLoop=dirname(dirname($this->currentdirectory)).'/./';
            // $this->trimoutDoubleSlashes();
    
                rmdir(trim($this->currentdirectory));
           
     
        
            return $this->{__FUNCTION__}($nextLoop);
        }
        return true;
    }
    protected function avoidShallowCopyDelete()
    {
        // if(!$this->calledFlag)
        // {
        //     // if(is_null(config('constants.ApiVersion')))
        //     // {
        //     //     $this->rootPath.='/v1/';
        //     // }
        //     // else
        //     // {
        //     //     $version=config('constants.ApiVersion');
        //     //     $this->rootPath.="/$version/";
        //     // }
        // }
        $this->calledFlag=true;
    }
    protected function executionWithStrReplace(RouteConst $rootPath,array $replaceString):bool
    {
        // $this->avoidShallowCopyDelete();
        $rootPathMethod=$rootPath->value;
        $dirname=trim(dirname($this->argument('name')));
        $flag=false;
        if($dirname=='.')
        {
            $this->currentdirectory= $rootPathMethod($this->rootPath."/./");
        }
        else
        {
            $this->currentdirectory= $rootPathMethod($this->rootPath.$dirname."/./");
        }
        
        $this->trimoutDoubleSlashes();
        if(!is_dir($this->currentdirectory))
        {
        
            return false;
        }

    
        $scanningFile=File::allFiles($this->currentdirectory);
        
        // $this->info($this->argument('name'));
        // return;
 
        $file=strtolower(pathinfo(($this->argument('name')),PATHINFO_FILENAME));
        // dd($scanningFile);
        $flag=true;
        foreach($scanningFile as $out)
        {
            $currentfilename=strtolower(pathinfo(pathinfo($out->getFilename(),PATHINFO_FILENAME),PATHINFO_FILENAME));
            ($file==$currentfilename&&!is_dir($file))&&$flag=File::delete($out); // delete file
        };
        if($flag)
        {
            // dd($this->currentdirectory);
            return $this->unlinkdirectories();
    
        }
        return $flag;
    }
    protected function executionDelete(RouteConst $rootPath):bool
    {
        // $this->avoidShallowCopy();
        $rootPathMethod=$rootPath->value;
        $dirname=trim(dirname($this->argument('name')));
        $flag=false;
        if($dirname=='.')
        {
            $this->currentdirectory= $rootPathMethod($this->rootPath."/./");
        }
        else
        {
            $this->currentdirectory= $rootPathMethod($this->rootPath.$dirname."/./");
        }
        
        $this->trimoutDoubleSlashes();
        if(!is_dir($this->currentdirectory))
        {
        
            return false;
        }

    
        $scanningFile=File::allFiles($this->currentdirectory);
        
        // $this->info($this->argument('name'));
        // return;
 
        $file=strtolower(pathinfo(($this->argument('name')),PATHINFO_FILENAME));
        // dd($scanningFile);
        $flag=true;
        foreach($scanningFile as $out)
        {
            $currentfilename=strtolower(pathinfo(pathinfo($out->getFilename(),PATHINFO_FILENAME),PATHINFO_FILENAME));
            if($file==$currentfilename&&!is_dir($file))
            {
                $realPath=$out->getRealPath();
                $flag?$flag=File::delete($out):File::delete($out); // 一旦flag 否定則不繼續讓flag賦值
                
                $this->info("deleted:$realPath");
            }
            // ($file==$currentfilename&&!is_dir($file))&&$flag=File::delete($out); // delete file
        };
        if($flag)
        {
            // dd($this->currentdirectory);
            return $this->unlinkdirectories();
    
        }
        return $flag;
    }
    protected function executionDeleteWithName(RouteConst $rootPath,$name):bool
    {

        // $this->avoidShallowCopy();
        $rootPathMethod=$rootPath->value;

        $flag=false;
        if(is_null($this->argument('name')))
        {
            $this->currentdirectory= $rootPathMethod($this->rootPath."/./");
        }
        else
        {
            $this->currentdirectory= $rootPathMethod($this->rootPath.$this->argument('name')."/./");
        }
        $this->trimoutDoubleSlashes();
        // dd($this->currentdirectory);
        if(!is_dir($this->currentdirectory))
        {
        
            $this->info('directory not exists');
            return false;
        }
    
        $scanningFile=File::allFiles($this->currentdirectory);
        
        // $this->info($this->argument('name'));
        // return;
        
        $file=strtolower($name);
        // dd($scanningFile);
        foreach($scanningFile as $out)
        {
            $currentfilename=strtolower(pathinfo(pathinfo($out->getFilename(),PATHINFO_FILENAME),PATHINFO_FILENAME));
            ($file==$currentfilename)&&$flag=File::delete($out); // delete file
        };
        if($flag)
        {
            // dd($this->currentdirectory);
            return $this->unlinkdirectories();
    
        }
        return true;
    }
}