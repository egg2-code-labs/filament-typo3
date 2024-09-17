<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Egg2CodeLabs\FilamentTypo3\PageTree;
use Exception;
use Filament\Pages\Page;
use ReflectionClass;

trait HasPageTree
{
    /**
     * @var bool
     */
    public static bool $hasSidebar = true;

    /**
     * @return PageTree
     */
    public static function getSidebar(): PageTree
    {
        return PageTree::make();
    }

    /**
     * public function mountHasPageSidebar
     * Register automatically view if available and activated
     */
    public function bootHasPageTree(): void
    {
        if (static::$hasSidebar) {
            static::${'view'} = 'filament-typo3::proxy';
        }
    }

    /**
     * public function getIncludedSidebarView
     * Return the view that will be used in the sidebar proxy.
     *
     * @return string \Filament\Pages\Page View to be included
     * @throws Exception
     */
    public function getIncludedSidebarView(): string
    {
        if (is_subclass_of($this, Page::class)) {
            $props = collect(
                (new ReflectionClass($this))->getDefaultProperties()
            );

            if ($props->get('view')) {
                return $props->get('view');
            }
        }

        throw new Exception('No view detected for the Sidebar. Implement Filament\Pages\Page object with valid static $view');
    }

    /**
     * @return int[]
     */
    public function getSidebarWidths(): array
    {
        return config('filament-typo3   .sidebar_width') ?? [
            'sm' => 12,
            'md' => 3,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 3,
        ];
    }
}
