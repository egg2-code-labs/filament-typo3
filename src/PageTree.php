<?php

namespace Egg2CodeLabs\FilamentTypo3;

use App\Models\Page;
use Closure;
use Egg2CodeLabs\FilamentTypo3\Scopes\Typo3AccessScope;
use Filament\Support\Concerns\EvaluatesClosures;
use Illuminate\Support\Collection;

class PageTree
{
    use EvaluatesClosures;

    protected string|Closure|null $title = null;
    protected string|Closure|null $description = null;

    /**
     * @param string|null $title
     * @param string|null $description
     */
    public function __construct(null|string $title = null, null|string $description = null)
    {
        $this->title = $title;
        $this->description = $description;
    }

    /**
     * @return static
     */
    public static function make(): static
    {
        return new static(
            title: 'Pages',
            description: 'Tree of pages'
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
     * @return Collection<Page>
     */
    public function getPages(): Collection
    {
        return Page::query()
            ->withoutGlobalScopes([
                Typo3AccessScope::class
            ])
            ->select(['id'])
            ->where('pid', 0)
            ->with('children')
            ->get();
    }
}
