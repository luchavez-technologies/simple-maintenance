<?php

namespace Luchavez\SimpleMaintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Luchavez\SimpleMaintenance\Http\Requests\ToggleMaintenanceModeRequest;
use Luchavez\SimpleMaintenance\Repositories\MaintenanceLogRepository;
use Illuminate\Http\JsonResponse;

/**
 * Class ToggleMaintenanceModeController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class ToggleMaintenanceModeController extends Controller
{
    /**
     * Toggle Maintenance Mode
     *
     * @group Maintenance Mode
     *
     * @param  ToggleMaintenanceModeRequest  $request
     * @param  MaintenanceLogRepository  $repository
     * @return JsonResponse
     */
    public function __invoke(ToggleMaintenanceModeRequest $request, MaintenanceLogRepository $repository): JsonResponse
    {
        $log = $repository->toggleMaintenanceMode($request);

        return simpleResponse()
            ->data($log)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }
}
