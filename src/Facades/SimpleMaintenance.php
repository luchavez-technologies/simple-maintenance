<?php

namespace Luchavez\SimpleMaintenance\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class SimpleMaintenance
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 *
 * @see \Luchavez\SimpleMaintenance\Services\SimpleMaintenance
 */
class SimpleMaintenance extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor(): string
    {
        return 'simple-maintenance';
    }
}
