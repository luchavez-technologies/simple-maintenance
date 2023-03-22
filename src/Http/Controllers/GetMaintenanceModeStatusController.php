<?php

namespace Luchavez\SimpleMaintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Luchavez\SimpleMaintenance\Repositories\MaintenanceLogRepository;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class GetMaintenanceModeStatusController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class GetMaintenanceModeStatusController extends Controller
{
    /**
     * Get Current Maintenance Status
     *
     * @group Maintenance Mode
     *
     * @param  Request  $request
     * @param  MaintenanceLogRepository  $repository
     * @return JsonResponse
     */
    public function __invoke(Request $request, MaintenanceLogRepository $repository): JsonResponse
    {
        $last_activity = $repository->builder()
            ->when(! $request->user(), fn (Builder $q) => $q->without('owner'))
            ->scopes('success')
            ->latest('finished_at')
            ->first();

        $data = [
            'is_under_maintenance' => app()->isDownForMaintenance(),
            'last_activity' => $last_activity,
        ];

        return customResponse()
            ->data($data)
            ->message('Successfully fetched maintenance status.')
            ->success()
            ->generate();
    }
}
