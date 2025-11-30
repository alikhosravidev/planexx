<?php

declare(strict_types=1);

namespace App\Traits;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * Trait LogsModelActivity.
 *
 * Handles conditional activity logging for Eloquent models using the Spatie activitylog package.
 * Logging is enabled only if the model has a `shouldLogActivity = true` property defined.
 *
 * @see https://spatie.be/docs/laravel-activitylog/v4/introduction
 *
 */
trait LogsModelActivity
{
    use LogsActivity;

    /**
     * Configure activity log options.
     */
    public function getActivitylogOptions(): LogOptions
    {
        $this->bootModelActivityLogging();

        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->dontLogIfAttributesChangedOnly($this->getGlobalBlacklist())
            ->logExcept($this->getGlobalBlacklist())
            ->useLogName('model-events')
        ;
    }

    /**
     * Enable or disable logging based on model property.
     */
    protected function bootModelActivityLogging(): void
    {
        if (!$this->shouldEnableLogging()) {
            $this->disableLogging();
        }
    }

    /**
     * Determine whether activity logging should be enabled for this model.
     *
     * @return bool true if model has `$shouldLogActivity = true`, otherwise false
     */
    protected function shouldEnableLogging(): bool
    {
        return property_exists($this, 'shouldLogActivity') && true === $this->shouldLogActivity;
    }

    public function getGlobalBlacklist(): array
    {
        return ['updated_at', 'cache'];
    }
}
