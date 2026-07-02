<?php

declare(strict_types=1);

namespace Egg2CodeLabs\FilamentTypo3\Scopes;

use Egg2CodeLabs\FilamentTypo3\Enums\Typo3AccessTabFieldsEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Collection;

/**
 * Apply the TYPO3 access scope to filter records based on visibility and timing.
 *
 * This scope filters records that:
 * - Are not hidden (unless HIDDEN field is disabled)
 * - Have starttime in the past or null (unless STARTTIME field is disabled)
 * - Have endtime in the future or null (unless ENDTIME field is disabled)
 * - Are sorted by the sorting field (unless sorting is disabled)
 */
final readonly class Typo3AccessScope implements Scope
{
    /**
     * @var Collection<Typo3AccessTabFieldsEnum> List of disabled fields
     */
    private Collection $disabledFields;

    private bool $sorting;

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
     *
     * @param Builder $builder The query builder to apply the scope to
     * @param Model $model The model being queried
     */
    public function apply(Builder $builder, Model $model): void
    {
        $now = now();
        $table = $model->getTable();

        $builder
            ->when(
                value: fn (): bool => $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::HIDDEN),
                callback: function (Builder $query) use ($table): Builder {
                    return $query->where("{$table}.hidden", 'false');
                }
            )
            ->when(
                value: fn (): bool => $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::STARTTIME),
                callback: function (Builder $query) use ($now, $table): Builder {
                    return $query->where(function (Builder $query) use ($now, $table): Builder {
                        return $query
                            ->whereNull("{$table}.starttime")
                            ->orWhere("{$table}.starttime", '<=', $now);
                    });
                }
            )
            ->when(
                value: fn (): bool => $this->disabledFields->doesntContain(Typo3AccessTabFieldsEnum::ENDTIME),
                callback: function (Builder $query) use ($now, $table): Builder {
                    return $query->where(function (Builder $query) use ($now, $table): Builder {
                        return $query
                            ->whereNull("{$table}.endtime")
                            ->orWhere("{$table}.endtime", '>=', $now);
                    });
                }
            )
            ->when(
                value: fn (): bool => $this->sorting,
                callback: function (Builder $query) use ($table): Builder {
                    return $query->orderBy("{$table}.sorting", 'asc');
                }
            );
    }
}
