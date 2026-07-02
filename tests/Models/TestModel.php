<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Models;

use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Egg2CodeLabs\FilamentTypo3\Traits\HasExpandablesTrait;
use Egg2CodeLabs\FilamentTypo3\Traits\Typo3AccessTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TestModel extends Model implements HasExpandablesInterface
{
    use HasFactory;
    use HasExpandablesTrait;
    use Typo3AccessTrait;

    /**
     * @var string The table name
     */
    protected $table = 'test_models';

    /**
     * @var array<string> The fillable attributes
     */
    protected $fillable = [
        'title',
        'hidden',
        'nav_hide',
        'starttime',
        'endtime',
        'sorting',
        'pid',
    ];

    /**
     * Get the parent relationship.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(TestModel::class, 'pid');
    }

    /**
     * Get the children relationship.
     */
    public function children(): HasMany
    {
        return $this->hasMany(TestModel::class, 'pid');
    }

    /**
     * Check if the model has children.
     */
    public function hasChildren(): bool
    {
        return $this->children()->exists();
    }

    /**
     * Get the Filament resource class for this model.
     */
    public static function getFilamentResource(): string
    {
        return \Egg2CodeLabs\FilamentTypo3\Tests\Resources\TestModelResource::class;
    }
}
