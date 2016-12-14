<?php

namespace App\Providers;

use Illuminate\Routing\Router;
use Dingo\Api\Routing\Router as ApiRouter;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
	/**
	* This namespace is applied to your controller routes.
			     *
			     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    protected $version = 'v1';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function boot(Router $router)
    {
        //
        parent::boot($router);
        /**
        * Production server URL fix for domain name;
        * e.g. local development ip = 192.168.xxx.xxx whereas
        * production should be like mydomain.com   
        */
        if(env('APP_ENV') == "production"){
            /** uncomment this on production server **/ 
            /** @var \Illuminate\Routing\UrlGenerator $url */
            $url = $this->app['url'];
            // Force the application URL
            $url->forceRootUrl(config('app.url'));
        }
    }
    /**
     * Define the routes for the application.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    public function map(Router $router,ApiRouter $apiRouter)
    {
       // $this->mapWebRoutes($router);

        $apiRouter->version($this->version, function ($apiRouter) use ($router) {
                   $apiRouter->group(['namespace' => $this->namespace], function ($api) use ($router) {
                       $router->group(['namespace' => $this->namespace,'middleware' => 'web'], function ($router) use ($api) {
                           require app_path('Http/routes.php');
                       });
                   });
               });

        //
    }
    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @param  \Illuminate\Routing\Router  $router
     * @return void
     */
    protected function mapWebRoutes(Router $router)
    {
        $router->group([
            'namespace' => $this->namespace, 'middleware' => 'web',
        ], function ($router) {
            require app_path('Http/routes.php');
        });
    }
}
