<?php

namespace Egg2CodeLabs\FilamentTypo3\Livewire\PageTree;

use App\Models\Page as PageModel;
use Egg2CodeLabs\FilamentTypo3\Interfaces\HasExpandablesInterface;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Page extends Component
{
    #[Locked]
    public string|int $pageId;

    /**
     * @var bool Whether this page is a root page or not
     */
    #[Locked]
    public bool $isRootPage = false;

    /**
     * @param HasExpandablesInterface|int|string $page
     * @param bool $isRootPage
     *
     * @return void
     */
    public function mount(HasExpandablesInterface|int|string $page, bool $isRootPage = false): void
    {
        /**
         * TODO: We need a dynamic way to push the correct class name into this component.
         */
        $this->pageId = $page instanceof PageModel
            ? $page->getKey()
            : $page;

        $this->isRootPage = $isRootPage;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('filament-typo3::components.page-tree.page');
    }

    /**
     * @return PageModel
     */
    #[Computed]
    public function page(): HasExpandablesInterface
    {
        $page = PageModel::query()
            ->withoutGlobalScopes()
            ->where('id', $this->pageId)
            ->orderBy('sorting')
            ->first();

        $children = $page
            ->children()
            ->withoutGlobalScopes()
            ->get();

        $page->setRelation('children', $children);

        return $page;
    }

    #[Computed]
    public function isOpen(): bool
    {
        return auth()->user()->expandables()
            ->where('expandable_type', $this->page()::class)
            ->where('expandable_id', $this->page()->getKey())
            ->exists();
    }

    /**
     * @return void
     */
    public function toggle(): void
    {
        $isOpen = !$this->isOpen();

        $user = auth()->user();

        if ($isOpen !== true) {
            $user->expandables()
                ->where('expandable_type', $this->page()::class)
                ->where('expandable_id', $this->page()->getKey())
                ->delete();
        }

        if ($isOpen === true) {
            $user->expandables()
                ->updateOrCreate(
                    attributes: [
                        'expandable_type' => $this->page()::class,
                        'expandable_id' => $this->page()->getKey(),
                        'user_id' => $user->id
                    ],
                    values: [
                        'expandable_type' => $this->page()::class,
                        'expandable_id' => $this->page()->getKey(),
                        'user_id' => $user->id
                    ]
                );
        }
    }
}
