<?php

namespace Luchavez\SimpleMaintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class GetMaintenanceModeStatusesController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class GetMaintenanceModeStatusesController extends Controller
{
    /**
     * Get Maintenance Mode Statuses
     *
     * @group Maintenance Mode
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        return customResponse()
            ->data(simpleMaintenance()->getStatuses())
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }
}
