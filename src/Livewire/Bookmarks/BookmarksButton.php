<?php

namespace Egg2CodeLabs\FilamentTypo3\Livewire\Bookmarks;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Computed;
use Livewire\Component;

/**
 * Class BookmarksButton
 *
 * Livewire component rendered in the Filament top bar via a render hook.
 * Displays a dropdown of user-defined bookmarks and allows adding/removing them.
 */
class BookmarksButton extends Component
{
    /**
     * Label for the new bookmark form.
     */
    public string $newBookmarkLabel = '';

    /**
     * URL for the new bookmark form.
     */
    public string $newBookmarkUrl = '';

    /**
     * Whether the "Add bookmark" form is expanded.
     */
    public bool $showAddForm = false;

    /**
     * Pre-fill the URL field with the current request URL.
     */
    public function mount(): void
    {
        $this->newBookmarkUrl = request()->url();
    }

    /**
     * Retrieve the authenticated user's bookmarks.
     *
     * @return array<string, string>
     */
    #[Computed]
    public function bookmarks(): array
    {
        $user = auth()->user();

        if ($user === null || ! method_exists($user, 'getBookmarks')) {
            return [];
        }

        return $user->getBookmarks();
    }

    /**
     * Save a new bookmark for the authenticated user.
     */
    public function addBookmark(): void
    {
        $this->validate([
            'newBookmarkLabel' => ['required', 'string', 'max:255'],
            'newBookmarkUrl' => ['required', 'url', 'max:2048'],
        ]);

        $user = auth()->user();

        if ($user === null || ! method_exists($user, 'addBookmark')) {
            return;
        }

        $user->addBookmark($this->newBookmarkUrl, $this->newBookmarkLabel);

        $this->showAddForm = false;
        $this->newBookmarkLabel = '';
        unset($this->bookmarks);
    }

    /**
     * Remove a bookmark by its URL.
     */
    public function removeBookmark(string $url): void
    {
        $user = auth()->user();

        if ($user === null || ! method_exists($user, 'removeBookmark')) {
            return;
        }

        $user->removeBookmark($url);
        unset($this->bookmarks);
    }

    /**
     * Toggle the "Add bookmark" inline form.
     */
    public function toggleAddForm(): void
    {
        $this->showAddForm = ! $this->showAddForm;
    }

    public function render(): View
    {
        return view('filament-typo3::livewire.bookmarks.bookmarks-button');
    }
}
