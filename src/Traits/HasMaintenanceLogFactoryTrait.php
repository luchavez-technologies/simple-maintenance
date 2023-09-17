<?php

namespace Luchavez\SimpleMaintenance\Traits;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Luchavez\SimpleMaintenance\Database\Factories\MaintenanceLogFactory;

/**
 * Trait HasMaintenanceLogFactoryTrait
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
trait HasMaintenanceLogFactoryTrait
{
    use HasFactory;

    /**
     * Create a new factory instance for the model.
     *
     * @return Factory
     */
    protected static function newFactory(): Factory
    {
        return MaintenanceLogFactory::new();
    }
}
