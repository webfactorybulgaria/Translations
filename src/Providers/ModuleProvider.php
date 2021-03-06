<?php

namespace TypiCMS\Modules\Translations\Providers;

use Illuminate\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use TypiCMS\Modules\Core\Shells\Services\Cache\LaravelCache;
use TypiCMS\Modules\Translations\Shells\Models\Translation;
use TypiCMS\Modules\Translations\Shells\Repositories\CacheDecorator;
use TypiCMS\Modules\Translations\Shells\Repositories\EloquentTranslation;

class ModuleProvider extends ServiceProvider
{
    public function boot()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/config.php', 'typicms.translations'
        );

        $modules = $this->app['config']['typicms']['modules'];

        $this->app['config']->set('typicms.modules', array_merge(['translations' => []], $modules));

        $this->loadViewsFrom(__DIR__.'/../resources/views/', 'translations');
        $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'translations');

        $this->publishes([
            __DIR__.'/../resources/views' => base_path('resources/views/vendor/translations'),
        ], 'views');
        $this->publishes([
            __DIR__.'/../database' => base_path('database'),
        ], 'migrations');
    }

    public function register()
    {
        $app = $this->app;

        /*
         * Register route service provider
         */
        $app->register('TypiCMS\Modules\Translations\Shells\Providers\RouteServiceProvider');

        /*
         * Sidebar view composer
         */
        $app->view->composer('core::admin._sidebar', 'TypiCMS\Modules\Translations\Shells\Composers\SidebarViewComposer');

        $app->bind('TypiCMS\Modules\Translations\Shells\Repositories\TranslationInterface', function (Application $app) {
            $repository = new EloquentTranslation(
                new Translation()
            );
            if (!config('typicms.cache')) {
                return $repository;
            }
            $laravelCache = new LaravelCache($app['cache'], 'translations', 10);

            return new CacheDecorator($repository, $laravelCache);
        });
    }
}
