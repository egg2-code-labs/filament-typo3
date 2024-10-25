<?php

namespace Egg2CodeLabs\FilamentTypo3\Traits;

use Closure;
use Egg2CodeLabs\FilamentTypo3\NodeTree;
use Exception;
use Filament\Pages\Page;
use Filament\Support\Concerns\EvaluatesClosures;
use ReflectionClass;

trait HasPageTree
{
    use EvaluatesClosures;

    /**
     * @var bool
     */
    public static bool|Closure $hasSidebar = true;

    /**
     * @return NodeTree
     */
    public function getSidebar(): NodeTree
    {
        return NodeTree::make($this->getModel());
    }

    /**
     * @return string
     */
    public function getModel(): string
    {
        return static::getResource()::getModel();
    }

    /**
     * Register view automatically if available and activated
     */
    public function bootHasPageTree(): void
    {
        if (static::evaluate(static::$hasSidebar)) {
            static::$view = 'filament-typo3::proxy';
        }
    }

    /**
     * Return view used in sidebar proxy
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
        return config('filament-typo3.sidebar_width') ?? [
            'sm' => 12,
            'md' => 3,
            'lg' => 3,
            'xl' => 3,
            '2xl' => 3,
        ];
    }
}
