<?php

namespace Egg2CodeLabs\FilamentTypo3\Tests\Resources\Pages;

use Egg2CodeLabs\FilamentTypo3\Tests\Resources\TestModelResource;
use Filament\Resources\Pages\EditRecord;

class EditTestModel extends EditRecord
{
    /**
     * The resource the page corresponds to.
     */
    protected static string $resource = TestModelResource::class;

    /**
     * The view to render for the page.
     */
    protected static string $view = 'filament.resources.test-model-resource.pages.edit-test-model';
}
