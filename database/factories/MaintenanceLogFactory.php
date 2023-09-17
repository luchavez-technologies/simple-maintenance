<?php

namespace Luchavez\SimpleMaintenance\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;

/**
 * Class MaintenanceLog
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceLogFactory extends Factory
{
    protected $model = MaintenanceLog::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            //
        ];
    }
}
