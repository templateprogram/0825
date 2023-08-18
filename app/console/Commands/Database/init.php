<?php

namespace App\Console\Commands\database;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class init extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    // php:artisan db:seed --class="className" --database=mysql2
    protected array $prefixarray=['mysql'=>'bmaker','en'=>'bmaken'];
    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        foreach($this->prefixarray as $key=>$out)
        {
            DB::setTablePrefix($out);

            $data = [
                '--path' => [
                    "/database/migrations" // Directory Path to migrations which require table prefix 
                ],
                '--database'=>$key,
                // '--seeder' => $key, //different seeder
                '--seed'=>true,
            ];
            $this->call('migrate:fresh', $data); // Next call the migration
        
      
        }


      
        // Artisan::call('migrate:fresh --seed --seeder=mysql');
    }
}
