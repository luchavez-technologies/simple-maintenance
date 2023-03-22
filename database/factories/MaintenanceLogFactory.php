<?php

namespace Luchavez\SimpleMaintenance\Database\Factories;

// Model
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Illuminate\Database\Eloquent\Factories\Factory;

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
