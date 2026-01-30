<?php

declare(strict_types=1);

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * All of the container bindings that should be registered.
     */
    public array $bindings = [
        // Add repository bindings here
        // Example: UserRepositoryInterface::class => UserRepository::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        // Register repositories as singletons
        foreach ($this->bindings as $interface => $implementation) {
            $this->app->singleton($interface, $implementation);
        }
    }
}
