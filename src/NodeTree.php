<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Closure;
use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Egg2CodeLabs\FilamentTypo3\Scopes\Typo3AccessScope;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

/**
 * Node tree structure for TYPO3-style page trees.
 *
 * Provides functionality to build and manage hierarchical node structures
 * with support for expandable nodes and access control.
 */
class NodeTree
{
    use EvaluatesClosures;

    /**
     * @var string|Closure|null The title of the node tree
     */
    protected string|Closure|null $title = null;

    /**
     * @var string|Closure|null The description of the node tree
     */
    protected string|Closure|null $description = null;

    /**
     * @var string|Closure|null The model class for the nodes
     */
    protected string|Closure|null $model = null;

    /**
     * Create a new NodeTree instance.
     *
     * @param string|Closure|null $title The title of the node tree
     * @param string|Closure|null $description The description of the node tree
     * @param string|Closure|null $model The model class for the nodes
     */
    public function __construct(null|string $title = null, null|string $description = null, null|string $model = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->model = $model;
    }

    /**
     * Create a new NodeTree instance with default values.
     *
     * @param string|Closure|null $model The model class for the nodes
     */
    public static function make(string|Closure|null $model = null): static
    {
        return new static(
            title: 'Pages',
            description: 'Tree of pages',
            model: $model
        );
    }

    /**
     * Get the title of the node tree.
     *
     * @return string|null The title
     */
    public function getTitle(): null|string
    {
        return $this->evaluate($this->title);
    }

    /**
     * Set the title of the node tree.
     *
     * @param string|Closure $title The title to set
     * @return $this
     */
    public function setTitle(string|Closure $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the description of the node tree.
     *
     * @return string|null The description
     */
    public function getDescription(): null|string
    {
        return $this->evaluate($this->description);
    }

    /**
     * Set the description of the node tree.
     *
     * @param string|Closure $description The description to set
     * @return $this
     */
    public function setDescription(string|Closure $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Set the model class for the nodes.
     *
     * @param string $model The model class
     * @return $this
     */
    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the model class for the nodes.
     *
     * @return string The model class
     */
    public function getModel(): string
    {
        return $this->evaluate($this->model);
    }

    /**
     * Get the root nodes of the tree.
     *
     * @return Collection<HasExpandablesInterface> The root nodes
     */
    public function getNodes(): Collection
    {
        /** @var Model $model */
        $model = $this->getModel();

        return $model::query()
            ->withoutGlobalScopes([
                Typo3AccessScope::class
            ])
            ->select(['id'])
            ->where('pid', 0)
            ->with('children')
            ->get();
    }
}
