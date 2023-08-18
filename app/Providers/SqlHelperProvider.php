<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;
class SqlHelperProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // as you know that laravel does not provide raw input for columnName
        /*
        offical guide:
        PDO does not support binding column names. Therefore, you should never allow user input to dictate the column names referenced by your queries, including "order by" columns.
        */
        Builder::macro('fronted', function (){
            return $this->where('active',1)->where('front',1)->orderBy('sort','ASC')->orderBy('created_at','DESC')
            ;
        });
        Builder::macro('actived', function (){
            return $this->where('active',1)->orderBy('sort','ASC')->orderBy('created_at','DESC');
        }); 
        Model::unguard();
        Schema::defaultStringLength(191);
        Builder::macro('resolveJson',function(string $columnName="attributes",string $sortByColumn='sort',string $order='ASC'){

            
                return $this->orderByRaw("JSON_EXTRACT($columnName,'$.".$sortByColumn."') $order");
                
              
        });
    }
}
