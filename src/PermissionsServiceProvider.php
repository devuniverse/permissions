<?php

namespace Devuniverse\Permissions;

use Illuminate\Support\ServiceProvider;
use Config;
use App\User;

class PermissionsServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   *
   * @return void
   */
  public function register()
  {
    // register our controller
    $this->app->make('Devuniverse\Permissions\Controllers\PermissionsController');

    $this->loadViewsFrom(__DIR__.'/views', 'permissions');

    $this->commands([
      Console\Commands\AssignSuperadmin::class
    ]);
    $this->mergeConfigFrom(
        __DIR__.'/config/permissions.php', 'permissions'
    );
  }

  /**
   * Bootstrap services.
   *
   * @return void
   */
  public function boot()
  {
    //
    include __DIR__.'/routes.php';

    $this->publishes([
      __DIR__.'/config/permissions.php' => config_path('permissions.php'),
      __DIR__.'/config/extraperms.php' => base_path('bootstrap/extraperms.php'),
      __DIR__.'/config/extraroles.php' => base_path('bootstrap/extraroles.php'),
      __DIR__.'/public' => public_path('permissions'),
    ]);

    $this->loadMigrationsFrom(__DIR__.'/database/migrations');

    view()->composer('*', function ($view){
      // if(Config::get('permissions.mode') =='multi'){
        // $roles = \Devuniverse\Permissions\Models\Role::get();
      // }else{
        $roles = \Devuniverse\Permissions\Models\Role::get();
      // }
      $view->with('roles', $roles) ;
      if( \Auth::check() ){

        $luser  = \Devuniverse\Permissions\Models\User::find( \Auth::user()->id );
        $view->with('pxs', $luser);
        $view->with('puser', $luser);
        $view->with('permitted', $luser);

        $entityUrl = \Request()->entity;

        if(isset($entityUrl)){
          $ent = \Devuniverse\Permissions\Models\Entity::where('slug',$entityUrl)->first();
          if($ent){
            $view->with('entityid', $ent->id);
          }
        }


      }else{
        $view->with('pxs', []);
        $view->with('puser', []);
      }


      $permissionsPath = Config::get('permissions.mode')==='multi' ? \Request()->global_entity.'/'.Config::get('permissions.permissions_url') : Config::get('permissions.permissions_url');
      $view->with('permissionsUrl', $permissionsPath );
    });

  }
}
