<?php

namespace Luchavez\SimpleMaintenance\Repositories;

use Carbon\Carbon;
use DateInterval;
use DateTimeInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Luchavez\SimpleMaintenance\DataFactories\MaintenanceLogDataFactory;
use Luchavez\SimpleMaintenance\Http\Requests\MaintenanceLog\UpdateMaintenanceLogRequest;
use Luchavez\SimpleMaintenance\Jobs\AnnounceMaintenanceToggleJob;
use Luchavez\SimpleMaintenance\Jobs\ToggleMaintenanceModeJob;
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Luchavez\StarterKit\Abstracts\BaseRepository;

/**
 * Class MaintenanceLogRepository
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceLogRepository extends BaseRepository
{
    /**
     * @param  Model|array|int|string|null  $id
     * @param  mixed  $attributes
     * @return Model|Collection|array|null
     */
    public function update(Model|array|int|string $id = null, mixed $attributes = []): Model|Collection|array|null
    {
        $tags = null;

        if ($attributes instanceof UpdateMaintenanceLogRequest) {
            $tags = $attributes->get('tags');
            $attributes = $attributes->validated();
        }

        // Get the model instance
        $log = $this->get($id);

        // Manual update
        foreach ($attributes as $key => $value) {
            $log->$key = $value;
        }

        if ($log->isDirty()) {
            $log->save();
        }

        // Sync tags when supplied
        if ($log instanceof MaintenanceLog && $tags) {
            $log->syncTagsWithType(tags: $tags, type: 'maintenance-logs');

            // Refresh Model
            $log->refresh();
        }

        return $log;
    }

    /**
     * @param  Model|int|array|string|null  $id
     * @param  mixed|null  $attributes
     * @return Model|Collection|array|null
     *
     * @throws AuthorizationException
     */
    public function delete(Model|int|array|string $id = null, mixed $attributes = null): Model|Collection|array|null
    {
        if ($id instanceof MaintenanceLog) {
            // Throw error if the log has already been executed.
            if (! $id->isPending() && $id->isFinished()) {
                throw new AuthorizationException('Failed to delete maintenance log as it has already executed.');
            }

            // Cancel pending maintenance log.
            $id->markAsCancelled();

            return $id;
        }

        return null;
    }

    public function toggleMaintenanceMode(FormRequest $request)
    {
        $data = $request->validated();
        $user = $request->user();
        $tags = $request->get('tags', []);
        $announce_at = $request->get('announce_at');

        // Create Maintenance Log
        $log = $user->maintenanceLogs()->save(MaintenanceLogDataFactory::from($data)->make());

        // Attach Tags to Maintenance Log
        $log->syncTagsWithType(tags: $tags, type: 'maintenance-logs');

        // Refresh Model
        $log->refresh();

        // Dispatch a job
        $this->dispatchToggleMaintenanceModeJob($log);

        // Dispatch an announcement
        if ($announce_at) {
            $this->dispatchAnnounceMaintenanceToggleJob($log, $announce_at);
        }

        return $log;
    }

    /**
     * @param  MaintenanceLog  $log
     * @return void
     */
    public function dispatchToggleMaintenanceModeJob(MaintenanceLog $log): void
    {
        $job = ToggleMaintenanceModeJob::dispatch(log: $log);

        // If `php artisan down` and has scheduled_at, delay appropriately.
        if ($log->is_down && $schedule = $log->scheduled_at) {
            $job->delay($schedule);
        }
        // Else, just execute the job after response.
        else {
            $job->afterResponse();
        }
    }

    /**
     * @param  MaintenanceLog  $log
     * @param  DateInterval|DateTimeInterface|string|int  $announce_at
     * @return void
     */
    public function dispatchAnnounceMaintenanceToggleJob(MaintenanceLog $log, DateInterval|DateTimeInterface|string|int $announce_at = 'now'): void
    {
        $now = now();
        $announce_at = Carbon::parse($announce_at);

        if ($announce_at->isBefore($now)) {
            $announce_at = null;
        }

        $job = AnnounceMaintenanceToggleJob::dispatch(log: $log);

        // Only announce later if `announce_at` is valid and running `php artisan down`
        if ($announce_at && $log->is_down) {
            $job->delay($announce_at);
        } else {
            $job->afterResponse();
        }
    }
}
