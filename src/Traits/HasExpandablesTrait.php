<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Egg2CodeLabs\FilamentTypo3\Models\ExpandableState;
use Illuminate\Database\Eloquent\Relations\HasMany;

trait HasExpandablesTrait
{
    public function expandables(): HasMany
    {
        return $this->hasMany(ExpandableState::class, 'user_id');
    }
}
