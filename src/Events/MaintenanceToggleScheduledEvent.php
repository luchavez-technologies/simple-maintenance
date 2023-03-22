<?php

namespace Luchavez\SimpleMaintenance\Events;

use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Class MaintenanceToggleScheduledEvent
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceToggleScheduledEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public MaintenanceLog $log)
    {
        //
    }
}
