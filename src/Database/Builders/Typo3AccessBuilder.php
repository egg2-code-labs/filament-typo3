<?php

declare(strict_types=1);

namespace Egg2CodeLabs\FilamentTypo3\Database\Builders;

use Illuminate\Database\Eloquent\Builder;

/**
 * A dedicated Eloquent query builder that exposes Typo3 access-control
 * constraints as first-class builder methods.
 *
 * Models that target Laravel 12+ can bind this builder via the attribute:
 *
 *   #[UseEloquentBuilder(Typo3AccessBuilder::class)]
 *   class Page extends Model { … }
 *
 * On older Laravel versions the builder can be activated by overriding the
 * `newEloquentBuilder` method on the model:
 *
 *   public function newEloquentBuilder($query): Typo3AccessBuilder
 *   {
 *       return new Typo3AccessBuilder($query);
 *   }
 *
 * @template TModel of \Illuminate\Database\Eloquent\Model
 *
 * @extends Builder<TModel>
 */
class Typo3AccessBuilder extends Builder
{
    /**
     * Exclude records that have the `hidden` flag set to true.
     */
    public function whereNotHidden(?string $table = null): static
    {
        $table = $this->resolveTable($table);

        return $this->where("{$table}.hidden", false);
    }

    /**
     * Only include records whose `starttime` has already passed (or is not set).
     */
    public function whereWithinStarttime(?string $table = null): static
    {
        $now = now();
        $table = $this->resolveTable($table);

        return $this->where(function (Builder $query) use ($now, $table): void {
            $query
                ->whereNull("{$table}.starttime")
                ->orWhere("{$table}.starttime", '<=', $now);
        });
    }

    /**
     * Only include records whose `endtime` has not yet passed (or is not set).
     */
    public function whereWithinEndtime(?string $table = null): static
    {
        $now = now();
        $table = $this->resolveTable($table);

        return $this->where(function (Builder $query) use ($now, $table): void {
            $query
                ->whereNull("{$table}.endtime")
                ->orWhere("{$table}.endtime", '>=', $now);
        });
    }

    /**
     * Order results by the `sorting` column ascending.
     */
    public function orderBySorting(?string $table = null): static
    {
        $table = $this->resolveTable($table);

        return $this->orderBy("{$table}.sorting", 'asc');
    }

    /**
     * Convenience method that applies all Typo3 access constraints at once.
     *
     * Pass the field names you want to *skip* in `$disabledFields`, e.g.:
     *
     *   Post::query()->applyTypo3Access(disableHidden: true)->get();
     */
    public function applyTypo3Access(
        ?string $table = null,
        bool $disableHidden = false,
        bool $disableStarttime = false,
        bool $disableEndtime = false,
        bool $sorting = true,
    ): static {
        if (! $disableHidden) {
            $this->whereNotHidden($table);
        }

        if (! $disableStarttime) {
            $this->whereWithinStarttime($table);
        }

        if (! $disableEndtime) {
            $this->whereWithinEndtime($table);
        }

        if ($sorting) {
            $this->orderBySorting($table);
        }

        return $this;
    }

    /**
     * Resolve the table prefix to use in column qualifications.
     *
     * Falls back to the table of the model bound to this builder when no
     * explicit table is provided.
     */
    private function resolveTable(?string $table): string
    {
        return $table ?? $this->getModel()->getTable();
    }
}
