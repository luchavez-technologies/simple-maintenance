<?php

namespace Luchavez\SimpleMaintenance\DataFactories;

use Luchavez\SimpleMaintenance\Models\MaintenanceLog;
use Luchavez\StarterKit\Abstracts\BaseDataFactory;
// Model
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

/**
 * Class MaintenanceLogDataFactory
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceLogDataFactory extends BaseDataFactory
{
    /**
     * @var bool
     */
    public bool $is_down;

    /**
     * @var string|null
     */
    public string|null $title = null;

    /**
     * @var string|null
     */
    public string|null $description = null;

    /**
     * @var string|null
     */
    public string|null $secret = null;

    /**
     * @var Carbon|null
     */
    public Carbon|null $scheduled_at = null;

    /**
     * @return Builder
     *
     * @example User::query()
     */
    public function getBuilder(): Builder
    {
        return MaintenanceLog::query();
    }

    /***** FROM BASEDATAFACTORY *****/

    /**
     * To avoid duplicate entries on database, checking if the model already exists by its unique keys is a must.
     *
     * @return array
     */
    public function getUniqueKeys(): array
    {
        return [
            //
        ];
    }

    /**
     * This is to avoid merging incorrect fields to Eloquent model. This is used on `mergeFieldsToModel()`.
     *
     * @return array
     */
    public function getExceptKeys(): array
    {
        return [
            //
        ];
    }

    /***** FROM BASEJSONSERIALIZABLE *****/

    /**
     * @return array
     */
    public function getFieldAliases(): array
    {
        return [];
    }
}
