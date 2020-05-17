<?php



namespace Shop\Providers;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;





use Illuminate\Support\ServiceProvider;



class ShopProviders extends ServiceProvider

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
        $basname=basename(dirname(__DIR__,1));
        config([
            $basname=>File::getRequire(__DIR__ . bs() . '..'.bs() . 'config'.bs().'routes.php'),
        ]);
        $this->loadRoutesFrom(__DIR__ . bs() . '..'.bs() . 'routes'.bs().'Shop.php');
        $this->app->bind(
            'Shop\Interfaces\ShopInterface',
            'Shop\Reposatries\ShopReposatry'
        );
    }

}

