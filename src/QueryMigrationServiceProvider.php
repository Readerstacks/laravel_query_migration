<?php

namespace Readerstacks\QueryMigration;

use Illuminate\Support\ServiceProvider;
 
class QueryMigrationServiceProvider extends ServiceProvider
{

    protected $commands = [
        'Readerstacks\QueryMigration\Commands\MigrateCommand',
        
    ];
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
         
        $this->commands($this->commands);
        $this->publishes([
            __DIR__.'/query_migration_config.php' => config_path('query_migration_config.php','querymigrationconfig'),
        ]);
       
        $this->loadRoutesFrom(__DIR__.'/routes/web.php');
        $this->loadMigrationsFrom(__DIR__.'/migrations');
        $this->loadViewsFrom(__DIR__.'/resources/views', 'QueryMigration');
        // $this->publishes([
        //     __DIR__.'/views' => base_path('resources/views/aman'),
        // ]);
    
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        
        $this->mergeConfigFrom(
            __DIR__.'/query_migration_config.php' ,'query_migration_config'
        );
        //   $this->app->make('Aman\SeoManagaer\Controllers\CrudController');
    }
}
