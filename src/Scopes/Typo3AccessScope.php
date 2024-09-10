<?php

namespace Egg2CodeLabs\FilamentTypo3\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class Typo3AccessScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model): void
    {
        $now = now();

        $builder
            /**
             * Don't load entries that are hidden and should not be displayed.
             */
            ->where('hidden', false)
            ->where(function (Builder $query) use ($now) {
                /**
                 * Only load entries that either don't have a starttime, or that have a starttime in the past.
                 */
                $query
                    ->whereNull('starttime')
                    ->orWhere('starttime', '<', $now);
            })
            ->where(function (Builder $query) use ($now) {
                /**
                 * Only load entries that either don't have an endtime, or that ahve an endtime in the future.
                 */
                $query
                    ->whereNull('endtime')
                    ->orWhere('endtime', '>', $now);
            })
            ->orderBy('sorting', 'asc');
    }
}
