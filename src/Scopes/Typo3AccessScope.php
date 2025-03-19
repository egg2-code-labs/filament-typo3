<?php

namespace Egg2CodeLabs\FilamentTypo3\Scopes;

use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\Typo3AccessTabFieldsEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Collection;

class Typo3AccessScope implements Scope
{
    /**
     * @param Collection<Typo3AccessTabFieldsEnum> $disabledFields
     */
    private readonly Collection $disabledFields;
    private readonly bool $sorting;

    /**
     * @param array<Typo3AccessTabFieldsEnum>|Collection<Typo3AccessTabFieldsEnum> $disabledFields
     */
    public function __construct(array|Collection $disabledFields = [], bool $sorting = true)
    {
        $this->disabledFields = collect($disabledFields);
        $this->sorting = $sorting;
    }

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
            ->when(
                value: fn () => $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::HIDDEN),
                callback: function (Builder $query) {
                    return $query->where('hidden', false);
                }
            )
            /**
             * Only load entries that either don't have a starttime, or that have a starttime in the past.
             */
            ->when(
                value: fn () => $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::STARTTIME),
                callback: function (Builder $query) use ($now) {
                    return $query->where(function (Builder $query) use ($now) {
                        return $query
                            ->whereNull('starttime')
                            ->orWhere('starttime', '<', $now);
                    });
                }
            )
            /**
             * Only load entries that either don't have an endtime, or that ahve an endtime in the future.
             */
            ->when(
                value: fn () => $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::ENDTIME),
                callback: function (Builder $query) use ($now) {
                    return $query->where(function (Builder $query) use ($now) {
                        return $query
                            ->whereNull('endtime')
                            ->orWhere('endtime', '>', $now);
                    });
                }
            )
            ->when(
                value: fn () => $this->sorting === true,
                callback: function (Builder $query) {
                    return $query->orderBy('sorting', 'asc');
                }
            );
    }
}
