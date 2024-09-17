<?php

namespace Egg2CodeLabs\FilamentTypo3;

use Closure;
use Filament\Support\Concerns\EvaluatesClosures;

class PageTree
{
    use EvaluatesClosures;

    protected string|Closure|null $title = null;
    protected string|Closure|null $description = null;

    public function __construct()
    {
    }

    public static function make(): static
    {
        return new static();
    }

    public function getTitle(): null|string
    {
        return $this->evaluate($this->title);
    }

    public function setTitle(string|Closure $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): null|string
    {
        return $this->evaluate($this->description);
    }

    public function setDescription(string|Closure $description): static
    {
        $this->description = $description;

        return $this;
    }
}
