<?php

namespace Luchavez\SimpleMaintenance\Events\MaintenanceLog;

use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Pagination\LengthAwarePaginator;
// Model
use Illuminate\Queue\SerializesModels;

/**
 * Class MaintenanceLogCollectedEvent
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceLogCollectedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public MaintenanceLog|Collection|LengthAwarePaginator $maintenance_log)
    {
        //
    }
}
