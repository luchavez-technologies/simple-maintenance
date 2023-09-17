<?php

namespace Luchavez\SimpleMaintenance\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Luchavez\SimpleMaintenance\Events\MaintenanceToggleSuccessfulEvent;
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;

/**
 * Class InvokeMaintenanceModeJob
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ToggleMaintenanceModeJob implements ShouldQueue
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

        $is_under_maintenance = app()->isDownForMaintenance();

        // Mark as failed if intent (up/down) is the same as the current status (up/down).
        if ($this->log->is_down == $is_under_maintenance) {
            $this->log->markAsFailed();

            return;
        }

        // Run `php artisan up`
        if (! $this->log->is_down) {
            Artisan::call('up');
        }
        // Run `php artisan down`
        else {
            Artisan::call('down', [
                '--secret' => $this->log->secret,
            ]);
        }

        // Mark log as successful
        $this->log->markAsSuccess();

        // Execute after success
        MaintenanceToggleSuccessfulEvent::dispatch($this->log);
    }
}
