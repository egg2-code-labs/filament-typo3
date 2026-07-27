<?php

namespace Egg2CodeLabs\FilamentTypo3\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Model for tracking expandable state of nodes in the UI.
 *
 * Stores which nodes are expanded for each user in the page tree.
 */
class ExpandableState extends Model
{
    use HasFactory;

    /**
     * @var string The table name
     */
    protected $table = "filament_typo3_expandable_state";

    /**
     * @var array<string> The fillable attributes
     */
    protected $fillable = ['expandable_type', 'expandable_id', 'user_id'];

    /**
     * Get the expandable model that this state belongs to.
     *
     * @return MorphTo The polymorphic relationship
     */
    public function expandable(): MorphTo
    {
        return $this->morphTo('expandable');
    }
}
