<?php

namespace Luchavez\SimpleMaintenance\Events\MaintenanceLog;

use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Events\Dispatchable;
// Model
use Illuminate\Queue\SerializesModels;

/**
 * Class MaintenanceLogCreatedEvent
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceLogCreatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(public MaintenanceLog|Collection $maintenance_log)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return Channel|PrivateChannel|array
     */
    public function broadcastOn(): Channel|PrivateChannel|array
    {
        return new PrivateChannel('maintenance-log');
    }
}
