<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use BackedEnum;
use Closure;
use Filament\Schemas\Components\Tabs\Tab;

abstract class AbstractCustomTab extends Tab
{
    /**
     * @var array<BackedEnum> List of fields excluded from rendering
     */
    protected Closure|array $exclude = [];
    /**
     * @var int
     */
    protected int $_columns = 2;

    /**
     * Get the schema for the whole tab
     *
     * @return array
     */
    abstract protected function getSchema(): array;

    /**
     * @param BackedEnum|string $fieldName
     *
     * @return BackedEnum
     */
    abstract protected function evaluateEnum(BackedEnum|string $fieldName): BackedEnum;

    /**
     * Add fields to the list of excluded fields
     *
     * @param Closure|array<BackedEnum|string> $exclude List of fields to exclude in this view
     *
     * @return $this
     */
    public function exclude(array|Closure $exclude): static
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * Get a sanitized list of excluded fields
     *
     * @return array
     */
    public function getExclude(): array
    {
        $exclude = $this->evaluate($this->exclude);

        return array_map(
            callback: fn (mixed $item) => $this->evaluateEnum($item),
            array: $exclude
        );
    }

    /**
     * setUp() is run through parent::__construct()
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->columns($this->_columns);
        $this->schema(
            $this->getSchema()
        );
    }

    /**
     * Check if whether a field is hidden
     *
     * @param BackedEnum|string $fieldName
     *
     * @return bool true if hidden, false if not hidden
     */
    protected function isFieldHidden(BackedEnum|string $fieldName): bool
    {
        return in_array(
            needle: $this->evaluateEnum($fieldName),
            haystack: $this->getExclude()
        );
    }
}
