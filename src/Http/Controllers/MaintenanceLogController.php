<?php

namespace Luchavez\SimpleMaintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Luchavez\SimpleMaintenance\Events\MaintenanceLog\MaintenanceLogArchivedEvent;
use Luchavez\SimpleMaintenance\Events\MaintenanceLog\MaintenanceLogCollectedEvent;
use Luchavez\SimpleMaintenance\Events\MaintenanceLog\MaintenanceLogRestoredEvent;
use Luchavez\SimpleMaintenance\Events\MaintenanceLog\MaintenanceLogShownEvent;
use Luchavez\SimpleMaintenance\Events\MaintenanceLog\MaintenanceLogUpdatedEvent;
use Luchavez\SimpleMaintenance\Http\Requests\MaintenanceLog\DeleteMaintenanceLogRequest;
use Luchavez\SimpleMaintenance\Http\Requests\MaintenanceLog\IndexMaintenanceLogRequest;
use Luchavez\SimpleMaintenance\Http\Requests\MaintenanceLog\RestoreMaintenanceLogRequest;
use Luchavez\SimpleMaintenance\Http\Requests\MaintenanceLog\ShowMaintenanceLogRequest;
use Luchavez\SimpleMaintenance\Http\Requests\MaintenanceLog\UpdateMaintenanceLogRequest;
use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Luchavez\SimpleMaintenance\Repositories\MaintenanceLogRepository;
use Luchavez\StarterKit\Exceptions\UnauthorizedException;
use Spatie\QueryBuilder\AllowedFilter;

/**
 * Class MaintenanceLogController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceLogController extends Controller
{
    /**
     * @param  MaintenanceLogRepository  $repository
     */
    public function __construct(protected MaintenanceLogRepository $repository)
    {
        //
    }

    /**
     * MaintenanceLog List
     *
     * @group Maintenance Mode
     *
     * @param  IndexMaintenanceLogRequest  $request
     * @return JsonResponse
     */
    public function index(IndexMaintenanceLogRequest $request): JsonResponse
    {
        $data = $this->repository->builder()
            ->allowedFilters([
                AllowedFilter::exact('is_down'),
                'title',
                'description',
                'secret',
                // Model-related
                AllowedFilter::trashed(),
                AllowedFilter::scope('pending'),
                AllowedFilter::scope('success'),
                AllowedFilter::scope('failed'),
                AllowedFilter::scope('cancelled'),
                AllowedFilter::scope('with_status'),
                AllowedFilter::scope('finished'),
                // Tag-related
                AllowedFilter::callback('with_any_tags', function (Builder $builder, $value) {
                    return $builder->withAnyTags($value, 'maintenance-logs');
                }),
                AllowedFilter::callback('with_all_tags', function (Builder $builder, $value) {
                    return $builder->withAllTags($value, 'maintenance-logs');
                }),
            ])
            ->allowedSorts([
                'is_down',
                'title',
                'description',
                'secret',
                'created_at',
                'updated_at',
                'deleted_at',
                'finished_at',
            ])
            ->defaultSort('-created_at');

        if ($request->has('full_data') === true) {
            $data = $data->get();
        } else {
            $data = $data->fastPaginate($request->get('per_page', 15));
        }

        event(new MaintenanceLogCollectedEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Show MaintenanceLog
     *
     * @group Maintenance Mode
     *
     * @param  ShowMaintenanceLogRequest  $request
     * @param  MaintenanceLog  $log
     * @return JsonResponse
     */
    public function show(ShowMaintenanceLogRequest $request, MaintenanceLog $log): JsonResponse
    {
        event(new MaintenanceLogShownEvent($log));

        return simpleResponse()
            ->data($log)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }

    /**
     * Update MaintenanceLog
     *
     * @group Maintenance Mode
     *
     * @param  UpdateMaintenanceLogRequest  $request
     * @param  MaintenanceLog  $log
     * @return JsonResponse
     */
    public function update(UpdateMaintenanceLogRequest $request, MaintenanceLog $log): JsonResponse
    {
        $log = $this->repository->update($log, $request);

        event(new MaintenanceLogUpdatedEvent($log));

        return simpleResponse()
            ->data($log)
            ->message('Successfully updated record.')
            ->success()
            ->generate();
    }

    /**
     * Archive MaintenanceLog
     *
     * @group Maintenance Mode
     *
     * @param  DeleteMaintenanceLogRequest  $request
     * @param  MaintenanceLog  $log
     * @return JsonResponse
     *
     * @throws UnauthorizedException
     */
    public function destroy(DeleteMaintenanceLogRequest $request, MaintenanceLog $log): JsonResponse
    {
        $log = $this->repository->delete($log);

        event(new MaintenanceLogArchivedEvent($log));

        return simpleResponse()
            ->data($log)
            ->message('Successfully archived record.')
            ->success()
            ->generate();
    }

    /**
     * Restore MaintenanceLog
     *
     * @group Maintenance Mode
     *
     * @param  RestoreMaintenanceLogRequest  $request
     * @param $log
     * @return JsonResponse
     */
    public function restore(RestoreMaintenanceLogRequest $request, $log): JsonResponse
    {
        $data = MaintenanceLog::withTrashed()->find($log)->restore();

        event(new MaintenanceLogRestoredEvent($data));

        return simpleResponse()
            ->data($data)
            ->message('Successfully restored record.')
            ->success()
            ->generate();
    }
}
