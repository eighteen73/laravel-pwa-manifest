<?php

namespace Eighteen73\PwaManifest;

use Eighteen73\PwaManifest\Console\Commands\BuildCommand;
use Eighteen73\PwaManifest\Events\BuildPwaManifest;
use Eighteen73\PwaManifest\Listeners\GenerateFiles;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class PwaManifestServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePublishing();
        $this->configureBlade();
        $this->configureAssetBuilders();
    }

    /**
     * Configure the publishable resources offered by the package.
     *
     * @return void
     */
    protected function configurePublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../stubs/pwa-manifest.php' => config_path('pwa-manifest.php'),
            ], 'pwa-manifest-config');
        }
    }

    /**
     * Configure the Blade component.
     *
     * @return void
     */
    protected function configureBlade()
    {
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'pwa-manifest');
        Blade::componentNamespace('Eighteen73\\PwaManifest\\View\\Components', 'pwa-manifest');
    }

    /**
     * Configure the event, lister and command that build the files.
     *
     * @return void
     */
    protected function configureAssetBuilders()
    {
        // Listen to our build event
        Event::listen(BuildPwaManifest::class, [
            GenerateFiles::class, 'handle',
        ]);

        // Artisan command
        $this->commands([
            BuildCommand::class,
        ]);
    }
}
