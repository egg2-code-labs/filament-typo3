<?php

namespace Egg2CodeLabs\FilamentTypo3\Interfaces;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @extends Model
 */
interface HasExpandablesInterface
{
    /**
     * @return BelongsTo
     */
    public function parent(): BelongsTo;

    /**
     * @return HasMany
     */
    public function children(): HasMany;

    /**
     * @return bool
     */
    public function hasChildren(): bool;

    /**
     * @return string
     */
    public static function getFilamentResource(): string;
}
