<?php

declare(strict_types=1);

namespace Egg2CodeLabs\FilamentTypo3\Scopes;

use Egg2CodeLabs\FilamentTypo3\Database\Builders\Typo3AccessBuilder;
use Egg2CodeLabs\FilamentTypo3\Forms\Components\Enums\Typo3AccessTabFieldsEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Support\Collection;

final readonly class Typo3AccessScope implements Scope
{
    /**
     * @param Collection<Typo3AccessTabFieldsEnum> $disabledFields
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
     */
    public function apply(Builder $builder, Model $model): void
    {
        $table = $model->getTable();

        // Wrap the incoming builder in a Typo3AccessBuilder so that the
        // constraint methods are available. Both builders share the same
        // underlying QueryBuilder instance, so every condition applied to
        // $typo3Builder is automatically reflected in the original $builder.
        $typo3Builder = new Typo3AccessBuilder($builder->getQuery());
        $typo3Builder->setModel($model);

        $typo3Builder->applyTypo3Access(
            table: $table,
            disableHidden: $this->disabledFields->contains(Typo3AccessTabFieldsEnum::HIDDEN),
            disableStarttime: $this->disabledFields->contains(Typo3AccessTabFieldsEnum::STARTTIME),
            disableEndtime: $this->disabledFields->contains(Typo3AccessTabFieldsEnum::ENDTIME),
            sorting: $this->sorting,
        );
    }
}
