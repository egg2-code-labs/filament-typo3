<?php

namespace Egg2CodeLabs\FilamentTypo3;

use App\Models\Page;
use Closure;
use Egg2CodeLabs\FilamentTypo3\Scopes\Typo3AccessScope;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class PageTree
{
    use EvaluatesClosures;

    protected string|Closure|null $title = null;
    protected string|Closure|null $description = null;
    protected string|Closure|null $model = null;

    /**
     * @param string|null $title
     * @param string|null $description
     * @param string|null $model
     */
    public function __construct(null|string $title = null, null|string $description = null, null|string $model = null)
    {
        $this->title = $title;
        $this->description = $description;
        $this->model = $model;
    }

    /**
     * @param string|Closure|null $model
     *
     * @return static
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
     * @return string|null
     */
    public function getTitle(): null|string
    {
        return $this->evaluate($this->title);
    }

    /**
     * @param string|Closure $title
     *
     * @return $this
     */
    public function setTitle(string|Closure $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): null|string
    {
        return $this->evaluate($this->description);
    }

    /**
     * @param string|Closure $description
     *
     * @return $this
     */
    public function setDescription(string|Closure $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @param string $model
     *
     * @return $this
     */
    public function setModel(string $model): static
    {
        $this->model = $model;

        return $this;
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return $this->evaluate($this->model);
    }

    /**
     * @return Collection<Page>
     */
    public function getPages(): Collection
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
