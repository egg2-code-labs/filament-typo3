<?php

declare(strict_types=1);

namespace Egg2CodeLabs\FilamentTypo3\Forms\Components\Actions;

use Filament\Forms\Components\Actions\Action;

final class OpenUrlAction extends Action
{
    /**
     * @var string HTML link target
     */
    protected string $target = '_blank';

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->icon('heroicon-o-link')
            ->tooltip(__('Open in new tab'))
            ->action(fn ($livewire, string $state) => $livewire->js(
                $this->getJsCode($state)
            ));
    }

    public static function make(null|string $name = 'Open'): static
    {
        return parent::make($name);
    }

    /**
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

    protected function getJsCode(string $url): string
    {
        return "window.open('{$url}', '{$this->target}').focus();";
    }
}
