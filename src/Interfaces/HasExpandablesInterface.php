<?php

namespace Egg2CodeLabs\FilamentTypo3\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Interface for models that support expandable tree structures.
 *
 * @extends Model
 */
interface HasExpandablesInterface
{
    /**
     * Get the parent relationship.
     *
     * @return BelongsTo The parent relationship
     */
    public function parent(): BelongsTo;

    /**
     * Get the children relationship.
     *
     * @return HasMany The children relationship
     */
    public function children(): HasMany;

    /**
     * Check if the model has children.
     *
     * @return bool True if the model has children, false otherwise
     */
    public function hasChildren(): bool;

    /**
     * Get the Filament resource class for this model.
     *
     * @return string The Filament resource class
     */
    public static function getFilamentResource(): string;
}
