<?php

namespace Luchavez\SimpleMaintenance\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\Tags\Tag;

/**
 * Class GetMaintenanceModeTagsController
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class GetMaintenanceModeTagsController extends Controller
{
    /**
     * Get Maintenance Reason Tags
     *
     * @group Maintenance Mode
     *
     * @param  Request  $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $data = Tag::getWithType('maintenance-logs')->all();

        return simpleResponse()
            ->data($data)
            ->message('Successfully collected record.')
            ->success()
            ->generate();
    }
}
