<?php



namespace Suppliers\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;





use Illuminate\Support\ServiceProvider;



class SuppliersProviders extends ServiceProvider

{

    /**

     * Register any application services.

     *

     * @return void

     */

    public function register()

    {


    }



    /**

     * Bootstrap any application services.

     *

     * @return void

     */

    public function boot()

    {
        $ds=DIRECTORY_SEPARATOR;
        config([
           'route'=>File::getRequire(__DIR__ . $ds . '..'.$ds . 'config'.$ds.'routes.php'),
        ]);
        $this->loadRoutesFrom(__DIR__ . $ds . '..'.$ds . 'routes'.$ds.'supplier.php');

    }

}

