@php
    use App\Models\Enums\PageDoctypesEnum;
@endphp


@props(['doctype'])

<div class="icon">
    @switch($doctype)
        @case(PageDoctypesEnum::STANDARD)
            <x-heroicon-o-document />
            @break
        @case(PageDoctypesEnum::EXTERNAL_LINK)
            <x-heroicon-o-link />
            @break
        @case(PageDoctypesEnum::SHORTCUT)
            <x-heroicon-o-arrow-top-right-on-square />
            @break
        @case(PageDoctypesEnum::LOGO)
            <x-heroicon-o-photo />
            @break
        @case(PageDoctypesEnum::FOLDER)
            <x-heroicon-o-folder />
            @break
        @default
            <x-heroicon-o-rectangle-stack />
    @endswitch
</div>
