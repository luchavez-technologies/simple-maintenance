<?php

/**
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */

use Luchavez\SimpleMaintenance\Services\SimpleMaintenance;

if (! function_exists('simpleMaintenance')) {
    /**
     * @return SimpleMaintenance
     */
    function simpleMaintenance(): SimpleMaintenance
    {
        return resolve('simple-maintenance');
    }
}

if (! function_exists('simple_maintenance')) {
    /**
     * @return SimpleMaintenance
     */
    function simple_maintenance(): SimpleMaintenance
    {
        return simpleMaintenance();
    }
}
