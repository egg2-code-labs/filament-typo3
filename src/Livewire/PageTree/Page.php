<?php

namespace Egg2CodeLabs\FilamentTypo3\Livewire\PageTree;

use App\Models\Page as PageModel;
use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Locked;
use Livewire\Component;

class Page extends Component
{
    #[Locked]
    public string|int $pageId;

    /**
     * @var bool Whether the child pages are displayed or not
     */
    public bool $isOpen = false;

    /**
     * @param PageModel|int|string $page
     *
     * @return void
     */
    public function mount(PageModel|int|string $page, bool $isOpen = false): void
    {
        $this->pageId = $page instanceof PageModel
            ? $page->getKey()
            : $page;

        $this->isOpen = $isOpen;
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('filament-typo3::page-tree.page');
    }

    /**
     * @return PageModel
     */
    #[Computed]
    public function page(): PageModel
    {
        return PageModel::query()
            ->where('id', $this->pageId)
            ->with('children')
            ->first();
    }

    /**
     * @return void
     */
    public function toggle(): void
    {
        $this->isOpen = !$this->isOpen;
    }
}
