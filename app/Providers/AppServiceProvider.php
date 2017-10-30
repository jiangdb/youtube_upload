<?php

namespace App\Providers;

//use Illuminate\Support\Facades\DB;
//use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        /*DB::listen(function($query){
            if(!empty($query->bindings)){
                $sql = $query->sql;
                $bindings = $query->bindings;
                foreach ($bindings as $replace){
                    $value = is_numeric($replace) ? $replace : "'".$replace."'";
                    $sql = preg_replace('/\?/', $value, $sql, 1);
                }
                Log::info($sql);
            }else{
                Log::info($query->sql);
            }
        });*/
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
