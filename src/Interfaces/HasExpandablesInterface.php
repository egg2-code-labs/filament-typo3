<?php

namespace Egg2CodeLabs\FilamentTypo3\Interfaces;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
}
