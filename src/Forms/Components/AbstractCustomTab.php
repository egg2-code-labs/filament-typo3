<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components;

use BackedEnum;
use Closure;
use Filament\Schemas\Components\Tabs\Tab;

/**
 * Abstract base class for custom TYPO3 tabs in Filament forms.
 *
 * Provides common functionality for tab components with field exclusion support.
 */
abstract class AbstractCustomTab extends Tab
{
    /**
     * @var array<BackedEnum>|Closure List of fields excluded from rendering
     */
    protected Closure|array $exclude = [];

    /**
     * Number of columns for the tab layout.
     */
    protected int $_columns = 2;

    /**
     * Get the schema for the whole tab.
     *
     * @return array<mixed> The form schema components
     */
    abstract protected function getSchema(): array;

    /**
     * Evaluate and convert field name to enum.
     *
     * @param BackedEnum|string $fieldName The field name to evaluate
     * @return BackedEnum The corresponding enum value
     */
    abstract protected function evaluateEnum(BackedEnum|string $fieldName): BackedEnum;

    /**
     * Add fields to the list of excluded fields.
     *
     * @param Closure|array<BackedEnum|string> $exclude List of fields to exclude in this view
     * @return $this
     */
    public function exclude(array|Closure $exclude): static
    {
        $this->exclude = $exclude;

        return $this;
    }

    /**
     * Get a sanitized list of excluded fields.
     *
     * @return array<BackedEnum> Array of enum values representing excluded fields
     */
    public function getExclude(): array
    {
        $exclude = $this->evaluate($this->exclude);

        return array_map(
            callback: fn (mixed $item): BackedEnum => $this->evaluateEnum($item),
            array: $exclude
        );
    }

    /**
     * setUp() is run through parent::__construct().
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
     * Check if a field is hidden.
     *
     * @param BackedEnum|string $fieldName The field name to check
     * @return bool True if hidden, false if not hidden
     */
    protected function isFieldHidden(BackedEnum|string $fieldName): bool
    {
        return in_array(
            needle: $this->evaluateEnum($fieldName),
            haystack: $this->getExclude()
        );
    }
}
