<?php

namespace Luchavez\SimpleMaintenance\Events\MaintenanceLog;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Queue\SerializesModels;
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;

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
