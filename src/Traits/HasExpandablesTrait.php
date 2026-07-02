<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Egg2CodeLabs\FilamentTypo3\Models\ExpandableState;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Trait for models that support expandable state tracking.
 *
 * Provides functionality to track which nodes are expanded in the UI
 * for each user.
 */
trait HasExpandablesTrait
{
    /**
     * Get the expandable states for this user.
     *
     * @return HasMany<ExpandableState, $this> The expandable states relationship
     */
    public function expandables(): HasMany
    {
        return $this->hasMany(ExpandableState::class, 'user_id');
    }
}
