<?php

namespace Luchavez\SimpleMaintenance\Providers;

use Luchavez\SimpleMaintenance\Console\Commands\InstallSimpleMaintenanceCommand;
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Luchavez\SimpleMaintenance\Repositories\MaintenanceLogRepository;
use Luchavez\SimpleMaintenance\Services\SimpleMaintenance;
use Luchavez\StarterKit\Abstracts\BaseStarterKitServiceProvider as ServiceProvider;
use Luchavez\StarterKit\Interfaces\ProviderDynamicRelationshipsInterface;

/**
 * Class SimpleMaintenanceServiceProvider
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class SimpleMaintenanceServiceProvider extends ServiceProvider implements ProviderDynamicRelationshipsInterface
{
    /**
     * @var array
     */
    protected array $commands = [
        InstallSimpleMaintenanceCommand::class,
    ];

    /**
     * @var string|null
     */
    protected string|null $route_prefix = null;

    /**
     * @var bool
     */
    protected bool $prefix_route_with_file_name = true;

    /**
     * @var bool
     */
    protected bool $prefix_route_with_directory = false;

    /**
     * Polymorphism Morph Map
     *
     * @link    https://laravel.com/docs/8.x/eloquent-relationships#custom-polymorphic-types
     *
     * @example [ 'user' => User::class ]
     *
     * @var array
     */
    protected array $morph_map = [
        'maintenance_log' => MaintenanceLog::class,
    ];

    /**
     * Laravel Observer Map
     *
     * @link    https://laravel.com/docs/8.x/eloquent#observers
     *
     * @example [ UserObserver::class => User::class ]
     *
     * @var array
     */
    protected array $observer_map = [];

    /**
     * Laravel Policy Map
     *
     * @link    https://laravel.com/docs/8.x/authorization#registering-policies
     *
     * @example [ UserPolicy::class => User::class ]
     *
     * @var array
     */
    protected array $policy_map = [];

    /**
     * Laravel Repository Map
     *
     * @example [ UserRepository::class => User::class ]
     *
     * @var array
     */
    protected array $repository_map = [
        MaintenanceLogRepository::class => MaintenanceLog::class,
    ];

    /**
     * Publishable Environment Variables
     *
     * @example [ 'HELLO_WORLD' => true ]
     *
     * @var array
     */
    protected array $env_vars = [
        'MAINTENANCE_MINIMUM_SCHEDULED_AT' => 'now',
        'MAINTENANCE_TOGGLE_MIDDLEWARE' => 'auth:api',
        'MAINTENANCE_STATUS_MIDDLEWARE' => 'auth:api',
        'MAINTENANCE_DEFAULT_MIDDLEWARE' => 'auth:api',
    ];

    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot(): void
    {
        parent::boot();
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register(): void
    {
        // Register the service the package provides.
        $this->app->singleton('simple-maintenance', fn () => new SimpleMaintenance());
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return ['simple-maintenance'];
    }

    /**
     * Console-specific booting.
     *
     * @return void
     */
    protected function bootForConsole(): void
    {
        // Publishing the configuration file.
        $this->publishes([
            __DIR__.'/../../config/simple-maintenance.php' => config_path('simple-maintenance.php'),
        ], 'simple-maintenance.config');

        // Registering package commands.
        $this->commands($this->commands);
    }

    /**
     * @return void
     *
     * @link   https://laravel.com/docs/8.x/eloquent-relationships#dynamic-relationships
     */
    public function registerDynamicRelationships(): void
    {
        starterKit()->getUserModel()::resolveRelationUsing('maintenanceLogs', function ($model) {
            return $model->hasMany(MaintenanceLog::class, MaintenanceLog::getOwnerIdColumn());
        });
    }

    /**
     * @return array
     */
    public function getDefaultApiMiddleware(): array
    {
        return [];
    }
}
