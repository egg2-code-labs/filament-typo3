<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Closure;
use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Egg2CodeLabs\FilamentTypo3\Scopes\Typo3AccessScope;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class NodeTree
{
    use EvaluatesClosures;

    protected string|Closure|null $title = null;

    protected string|Closure|null $description = null;

    protected string|Closure|null $model = null;

    public function __construct(null|string $title = null, null|string $description = null, null|string $model = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->model = $model;
    }

    public static function make(string|Closure|null $model = null): static
    {
        return new static(
            title: 'Pages',
            description: 'Tree of pages',
            model: $model
        );
    }

    public function getTitle(): null|string
    {
        return $this->evaluate($this->title);
    }

    /**
     * @return $this
     */
    public function setTitle(string|Closure $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): null|string
    {
        return $this->evaluate($this->description);
    }

    /**
     * @return $this
     */
    public function setDescription(string|Closure $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return $this
     */
    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    public function getModel(): string
    {
        return $this->evaluate($this->model);
    }

    /**
     * @return Collection<HasExpandablesInterface>
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
