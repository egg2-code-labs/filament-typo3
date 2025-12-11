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
    public function parent(): BelongsTo;

    public function children(): HasMany;

    public function hasChildren(): bool;

    public static function getFilamentResource(): string;
}
