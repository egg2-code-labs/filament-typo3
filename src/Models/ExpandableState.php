<?php

namespace Egg2CodeLabs\FilamentTypo3\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ExpandableState extends Model
{
    protected $table = "filament_typo3_expandable_state";

    protected $fillable = ['expandable_type', 'expandable_id', 'user_id'];

    /**
     * @return MorphTo
     */
    public function expandable(): MorphTo
    {
        return $this->morphTo('expandable');
    }
}
