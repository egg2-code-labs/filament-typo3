<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

/**
 * Trait HasBookmarksTrait
 *
 * Adds bookmark management to a user model.
 * Bookmarks are stored as a JSON array where the link target is the key and the label is the value.
 *
 * Usage: add `use HasBookmarksTrait;` to your User model and ensure the `bookmarks` column exists.
 */
trait HasBookmarksTrait
{
    /**
     * Initialize the trait and add the bookmarks cast.
     */
    protected function initializeHasBookmarksTrait(): void
    {
        $this->mergeCasts(['bookmarks' => 'array']);
    }

    /**
     * Get all bookmarks as an associative array of [url => label].
     *
     * @return array<string, string>
     */
    public function getBookmarks(): array
    {
        return $this->bookmarks ?? [];
    }

    /**
     * Add or update a bookmark.
     */
    public function addBookmark(string $url, string $label): void
    {
        $bookmarks = $this->getBookmarks();
        $bookmarks[$url] = $label;
        $this->update(['bookmarks' => $bookmarks]);
    }

    /**
     * Remove a bookmark by its URL.
     */
    public function removeBookmark(string $url): void
    {
        $bookmarks = $this->getBookmarks();
        unset($bookmarks[$url]);
        $this->update(['bookmarks' => $bookmarks]);
    }

    /**
     * Check whether a bookmark for the given URL already exists.
     */
    public function hasBookmark(string $url): bool
    {
        return array_key_exists($url, $this->getBookmarks());
    }
}
