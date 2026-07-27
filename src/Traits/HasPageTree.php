<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Closure;
use Egg2CodeLabs\FilamentTypo3\NodeTree;
use Exception;
use Filament\Pages\Page;
use Filament\Support\Concerns\EvaluatesClosures;
use ReflectionClass;

/**
 * Trait for pages that have a page tree sidebar.
 *
 * Provides functionality to automatically register and manage a sidebar
 * with a TYPO3-style page tree.
 */
trait HasPageTree
{
    use EvaluatesClosures;

    /**
     * Whether the page has a sidebar.
     */
    public static bool|Closure $hasSidebar = true;

    /**
     * Get the sidebar node tree instance.
     *
     * @return NodeTree The node tree instance
     */
    public function getSidebar(): NodeTree
    {
        return NodeTree::make($this->getModel());
    }

    /**
     * Get the model class for the page tree.
     *
     * @return string The model class
     */
    public function getModel(): string
    {
        return static::getResource()::getModel();
    }

    /**
     * Register view automatically if available and activated.
     */
    public function bootHasPageTree(): void
    {
        if (static::evaluate(static::$hasSidebar)) {
            static::$view = 'filament-typo3::proxy';
        }
    }

    /**
     * Return view used in sidebar proxy.
     *
     * @return string The view to include in the sidebar
     * @throws Exception If no view is detected
     */
    public function getIncludedSidebarView(): string
    {
        if (is_subclass_of($this, Page::class)) {
            $props = collect(
                new ReflectionClass($this)->getDefaultProperties()
            );

            if ($props->get('view')) {
                return $props->get('view');
            }
        }

        throw new Exception('No view detected for the Sidebar. Implement Filament\Pages\Page object with valid static $view');
    }

    /**
     * Get the sidebar widths for different screen sizes.
     *
     * @return int[] Array of widths for different breakpoints
     */
    public function getSidebarWidths(): array
    {
        return config('filament-typo3.sidebar_width', [
            'sm' => 12,
            'md' => 3,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 3,
        ]);
    }
}
