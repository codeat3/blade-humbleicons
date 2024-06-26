<?php

declare(strict_types=1);

namespace Codeat3\BladeHumbleicons;

use BladeUI\Icons\Factory;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

final class BladeHumbleiconsServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->registerConfig();

        $this->callAfterResolving(Factory::class, function (Factory $factory, Container $container) {
            $config = $container->make('config')->get('blade-humbleicons', []);

            $factory->add('humbleicons', array_merge(['path' => __DIR__ . '/../resources/svg'], $config));
        });
    }

    private function registerConfig(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/blade-humbleicons.php', 'blade-humbleicons');
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../resources/svg' => public_path('vendor/blade-humbleicons'),
            ], 'blade-humbleicons'); // TDOO: update this alias to `blade-humbleicons` in next major release

            $this->publishes([
                __DIR__ . '/../config/blade-humbleicons.php' => $this->app->configPath('blade-humbleicons.php'),
            ], 'blade-humbleicons-config');
        }
    }
}
