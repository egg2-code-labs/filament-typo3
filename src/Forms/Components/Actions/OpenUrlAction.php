<?php

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components\Actions;

use Filament\Forms\Components\Actions\Action;

class OpenUrlAction extends Action
{
    /**
     * @var string HTML link target
     */
    protected string $target = '_blank';

    /**
     * @param string|null $name
     *
     * @return static
     */
    public static function make(null|string $name = 'Open'): static
    {
        return parent::make($name);
    }

    /**
     * @param AnchorTargetEnum|string $target
     *
     * @return $this
     */
    public function target(AnchorTargetEnum|string $target): static
    {
        if (!$target instanceof AnchorTargetEnum) {
            $target = AnchorTargetEnum::from($target);
        }

        $this->target = $target;

        return $this;
    }

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-o-link')
            ->tooltip(__('Open in new tab'))
            ->action(fn ($livewire, $state) => $livewire->js(
                $this->getJsCode($state)
            ));
    }

    /**
     * @param string $url
     *
     * @return string
     */
    protected function getJsCode(string $url): string
    {
        return "window.open('{$url}', '{$this->target}').focus();";
    }
}
