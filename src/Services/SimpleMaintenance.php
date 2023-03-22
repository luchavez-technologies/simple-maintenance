<?php

namespace Luchavez\SimpleMaintenance\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\ItemNotFoundException;

/**
 * Class SimpleMaintenance
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class SimpleMaintenance
{
    /***** CONFIG RELATED *****/

    /**
     * @return Collection
     */
    public function getStatuses(): Collection
    {
        return collect(config('simple-maintenance.statuses'));
    }

    /**
     * @param  int  $code
     * @return array
     *
     * @throws ItemNotFoundException
     */
    public function getStatusByCode(int $code): array
    {
        return $this->getStatus('code', $code);
    }

    /**
     * @param  string  $name
     * @return array
     *
     * @throws ItemNotFoundException
     */
    public function getStatusByName(string $name): array
    {
        return $this->getStatus('name', $name);
    }

    /**
     * @param  string  $key
     * @return array
     *
     * @throws ItemNotFoundException
     */
    public function getStatusByKey(string $key): array
    {
        return $this->getStatus('key', $key);
    }

    /**
     * @param  string  $by
     * @param  int|string  $search
     * @return array
     *
     * @throws ItemNotFoundException
     */
    private function getStatus(string $by, int|string $search): array
    {
        return $this->getStatuses()
            ->map(function ($item, $key) {
                $item['key'] = $key;

                return $item;
            })
            ->where($by, $search)
            ->firstOrFail();
    }

    /**
     * @return string
     */
    public function getDefaultMiddleware(): string
    {
        return config('simple-maintenance.middlewares.default');
    }

    /**
     * @return string
     */
    public function getToggleMiddleware(): string
    {
        return config('simple-maintenance.middlewares.toggle');
    }

    /**
     * @return string|null
     */
    public function getStatusMiddleware(): string|null
    {
        return config('simple-maintenance.middlewares.status');
    }

    /**
     * @return string
     */
    public function getMinimumScheduledAt(): string
    {
        return config('simple-maintenance.minimum_scheduled_at');
    }
}
