<?php

namespace Luchavez\SimpleMaintenance\Jobs;

use Luchavez\SimpleMaintenance\Events\MaintenanceToggleScheduledEvent;
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

/**
 * Class AnnounceMaintenanceToggleJob
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class AnnounceMaintenanceToggleJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public MaintenanceLog $log)
    {
        //
    }

    /**
     * The unique ID of the job.
     *
     * @return string
     */
    public function uniqueId(): string
    {
        return $this->log->uuid;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        // If the log is already executed, no need to execute again.
        if (! $this->log->isPending()) {
            return;
        }

        MaintenanceToggleScheduledEvent::dispatch($this->log);
    }
}
