<?php

namespace azmolla\TransactionMiddleware;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class TransactionMiddlewareServiceProvider extends ServiceProvider
{
    /**
     * Register the service provider.
     *
     * Here we merge our package config with the application's config.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__ . '/../config/transaction-middleware.php',
            'transaction-middleware'
        );
    }

    /**
     * Boot the service provider.
     *
     * This method registers the middleware alias and, if configured, pushes
     * the middleware automatically to the 'web' and 'api' groups.
     *
     * @param  \Illuminate\Routing\Router $router
     * @return void
     */
    public function boot(Router $router)
    {
        // Publish the config file so users can override package defaults.
        $this->publishes([
            __DIR__ . '/../config/transaction-middleware.php' => config_path('transaction-middleware.php'),
        ], 'config');

        // Always register the alias for manual usage.
        $router->aliasMiddleware('transaction', \azmolla\TransactionMiddleware\Middleware\TransactionMiddleware::class);

        // Check if the configuration is set to auto-apply.
        if (config('transaction-middleware.auto_apply_web_api')) {
            // Automatically push the middleware to both 'web' and 'api' groups.
            $router->pushMiddlewareToGroup('web', \azmolla\TransactionMiddleware\Middleware\TransactionMiddleware::class);
            $router->pushMiddlewareToGroup('api', \azmolla\TransactionMiddleware\Middleware\TransactionMiddleware::class);
        } elseif (config('transaction-middleware.auto_apply_web')) {
            // Automatically push the middleware to the 'web' group.
            $router->pushMiddlewareToGroup('web', \azmolla\TransactionMiddleware\Middleware\TransactionMiddleware::class);
        } elseif (config('transaction-middleware.auto_apply_api')) {
            // Automatically push the middleware to the 'api' group.
            $router->pushMiddlewareToGroup('api', \azmolla\TransactionMiddleware\Middleware\TransactionMiddleware::class);
        }
    }
}
