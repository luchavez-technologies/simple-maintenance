<?php

namespace Luchavez\SimpleMaintenance\Models;

use Luchavez\SimpleMaintenance\Traits\HasMaintenanceLogFactoryTrait;
use Luchavez\StarterKit\Traits\ModelOwnedTrait;
use Luchavez\StarterKit\Traits\UsesUUIDTrait;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Tags\HasTags;

/**
 * Class MaintenanceLog
 *
 * @author James Carlo Luchavez <jamescarloluchavez@gmail.com>
 */
class MaintenanceLog extends Model
{
    use UsesUUIDTrait;
    use SoftDeletes;
    use ModelOwnedTrait;
    use HasTags;
    use HasMaintenanceLogFactoryTrait;

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName(): string
    {
        return 'uuid';
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        // write here...
        'deleted_at',
    ];

    protected $casts = [
        'is_down' => 'boolean',
        'scheduled_at' => 'datetime',
        'finished_at' => 'datetime',
    ];

    protected $hidden = [
        'secret',
    ];

    protected $with = [
        'owner',
        'tags',
    ];

    /***** RELATIONSHIPS *****/

    //

    /***** SCOPES *****/

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopePending(Builder $builder): Builder
    {
        return $builder->scopes(['withStatus' => 'pending']);
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeSuccess(Builder $builder): Builder
    {
        return $builder->scopes(['withStatus' => 'success']);
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeFailed(Builder $builder): Builder
    {
        return $builder->scopes(['withStatus' => 'failed']);
    }

    /**
     * @param  Builder  $builder
     * @return Builder
     */
    public function scopeCancelled(Builder $builder): Builder
    {
        return $builder->scopes(['withStatus' => 'cancelled']);
    }

    /**
     * @param  Builder  $builder
     * @param  string  $status
     * @return Builder
     */
    public function scopeWithStatus(Builder $builder, string $status): Builder
    {
        return $builder->where('status', simpleMaintenance()->getStatusByKey($status)['code']);
    }

    /**
     * @param  Builder  $builder
     * @param  bool  $bool
     * @return Builder
     */
    public function scopeFinished(Builder $builder, bool $bool = true): Builder
    {
        if ($bool) {
            return $builder->whereNotNull('finished_at');
        }

        return $builder->whereNull('finished_at');
    }

    /***** ACCESSORS & MUTATORS *****/

    /**
     * @param  int  $code
     * @return array
     */
    public function getStatusAttribute(int $code): array
    {
        return simpleMaintenance()->getStatusByCode($code);
    }

    /***** OTHER METHODS *****/

    /**
     * @return bool
     */
    public function isPending(): bool
    {
        return $this->isStatus('pending');
    }

    /**
     * @return bool
     */
    public function isSuccess(): bool
    {
        return $this->isStatus('success');
    }

    /**
     * @return bool
     */
    public function isFailed(): bool
    {
        return $this->isStatus('failed');
    }

    /**
     * @return bool
     */
    public function isCancelled(): bool
    {
        return $this->isStatus('cancelled');
    }

    /**
     * @param  string  $status
     * @return bool
     */
    private function isStatus(string $status): bool
    {
        return simpleMaintenance()->getStatusByKey($status)['code'] == $this->attributes['status'];
    }

    /**
     * @return bool
     */
    public function isFinished(): bool
    {
        return ! $this->isPending() && $this->finished_at;
    }

    /**
     * @param  bool  $force
     * @return bool
     */
    public function markAsPending(bool $force = false): bool
    {
        if (! $this->isPending() && $force) {
            return $this->markAs('pending');
        }

        return false;
    }

    /**
     * @param  bool  $force
     * @return bool
     */
    public function markAsSuccess(bool $force = false): bool
    {
        if ($this->isPending() || $force) {
            return $this->markAs('success');
        }

        return false;
    }

    /**
     * @param  bool  $force
     * @return bool
     */
    public function markAsFailed(bool $force = false): bool
    {
        if ($this->isPending() || $force) {
            return $this->markAs('failed');
        }

        return false;
    }

    /**
     * @param  bool  $force
     * @return bool
     */
    public function markAsCancelled(bool $force = false): bool
    {
        if ($this->isPending() || $force) {
            return $this->markAs('cancelled');
        }

        return false;
    }

    /**
     * @param  string  $status
     * @return bool
     */
    private function markAs(string $status): bool
    {
        $this->status = simpleMaintenance()->getStatusByKey($status)['code'];
        $this->finished_at = $status == 'pending' ? null : now();

        return $this->save();
    }
}
